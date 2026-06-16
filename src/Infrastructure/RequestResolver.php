<?php declare(strict_types=1);

namespace App\Infrastructure;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        if ($request->getMethod() === 'GET') {
            $data = json_encode($request->query->all(), JSON_THROW_ON_ERROR);
        } else {
            $data = $request->getContent();

            // Если body пустой, но данные пришли через обычную HTML-форму (POST)
            if (empty($data) && !empty($request->request->all())) {
                $data = json_encode($request->request->all(), JSON_THROW_ON_ERROR);
            }
        }

        try {
            $dto = $this->serializer->deserialize($data, $targetClass, 'json');
        } catch (\Throwable $e) {
            throw new HttpException(400, 'Invalid request payload formatting.', $e);
        }

        return [$dto];
    }
}
