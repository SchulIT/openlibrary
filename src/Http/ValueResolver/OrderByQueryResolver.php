<?php

namespace App\Http\ValueResolver;

use App\Repository\OrderBy;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class OrderByQueryResolver implements ValueResolverInterface {

    #[Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable {
        $attributes = $argument->getAttributesOfType(MapOrderByQueryParameter::class);

        if(count($attributes) !== 1) {
            return [ ];
        }

        if(!is_a($argument->getType(), OrderBy::class, true)) {
            return [ ];
        }

        /** @var MapOrderByQueryParameter $attribute */
        $attribute = $attributes[0];

        $column = $request->query->has($attribute->columnParameterName) ? $request->query->get($attribute->columnParameterName) : $attribute->defaultColumnName;
        if(!in_array($column, $attribute->allowedColumnNames)) {
            $column = $attribute->defaultColumnName;
        }

        $order = $request->query->has($attribute->orderParameterName) ? $request->query->get($attribute->orderParameterName) : OrderBy::DefaultOrderDirection;
        if(!in_array($order, OrderBy::AllowedOrderDirections)) {
            $order = OrderBy::DefaultOrderDirection;
        }

        return [
            new OrderBy($attribute->allowedColumnNames, $column, $order)
        ];
    }
}
