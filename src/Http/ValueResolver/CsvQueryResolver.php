<?php

namespace App\Http\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class CsvQueryResolver implements ValueResolverInterface {

    #[\Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable {
        $attributes = $argument->getAttributesOfType(MapCsvQueryParameter::class);

        if(count($attributes) !== 1) {
            return [ ];
        }

        if(!$request->query->has($argument->getName())) {
            return [ ];
        }

        /** @var MapCsvQueryParameter $attribute */
        $attribute = $attributes[0];
        $rawValue = $request->query->get($argument->getName());

        $rawValues = explode($attribute->delimiter, $rawValue);
        $values = array_map(fn($v) => filter_var($v, $attribute->filter, $attribute->flags | FILTER_NULL_ON_FAILURE), $rawValues);
        $filtered = array_filter($values, static fn ($v) => null !== $v);

        return [ $filtered ];
    }
}
