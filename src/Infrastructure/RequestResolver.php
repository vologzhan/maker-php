<?php declare(strict_types=1);

namespace App\Infrastructure;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class RequestResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        $targetClass = $argument->getType();

        if (!$targetClass || !str_starts_with($targetClass, 'App\\Request\\')) {
            return [];
        }

        try {
            $data = $this->extractData($request);

            $dto = $this->serializer->denormalize(
                $data,
                $targetClass,
                context: [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                ],
            );
        } catch (\JsonException $e) {
            throw new HttpException(400, 'Invalid JSON payload.', $e);
        } catch (ExceptionInterface $e) {
            throw new HttpException(400, 'Invalid request payload.', $e);
        }

        return [$dto];
    }

    private function extractData(Request $request): array
    {
        $body = [];

        if ($request->isMethod('GET')) {
            $body = $request->query->all();
        } else {
            $content = trim($request->getContent());

            if ($content !== '') {
                $body = json_decode(
                    $content,
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );
            } elseif ($request->request->count() > 0) {
                $body = $request->request->all();
            }
        }

        $pathParameters = array_filter(
            $request->attributes->all(),
            static fn (string $key): bool => !str_starts_with($key, '_'),
            ARRAY_FILTER_USE_KEY,
        );

        return array_replace(
            $body,
            $request->query->all(),
            $pathParameters,
        );
    }
}