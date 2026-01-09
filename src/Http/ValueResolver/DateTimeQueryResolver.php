<?php

namespace App\Http\ValueResolver;

use DateTimeImmutable;
use DateTimeInterface;
use Override;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class DateTimeQueryResolver implements ValueResolverInterface {
    public function __construct(
        private ClockInterface $clock
    ) {

    }

    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable {
        $attributes = $argument->getAttributesOfType(MapDateTimeQueryParameter::class);

        if(count($attributes) !== 1) {
            return [ ];
        }

        if(!is_a($argument->getType(), DateTimeInterface::class, true) || !$request->query->has($argument->getName())) {
            return [ ];
        }

        /** @var MapDateTimeQueryParameter $attribute */
        $attribute = $attributes[0];
        $value = $request->query->get($argument->getName());
        $class = DateTimeInterface::class === $argument->getType() ? DateTimeImmutable::class : $argument->getType();

        $date = $class::createFromFormat($attribute->format, $value, $this->clock->now()->getTimezone());
        return [ $date ];
    }
}
