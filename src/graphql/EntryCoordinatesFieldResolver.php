<?php

namespace nthmedia\entrygpscoordinates\graphql;

use craft\gql\base\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class EntryCoordinatesFieldResolver extends ObjectType
{
    /**
     * @inheritdoc
     */
    protected function resolve(mixed $source, array $arguments, mixed $context, ResolveInfo $resolveInfo): mixed
    {
        $fieldName = $resolveInfo->fieldName;
        return $source->$fieldName;
    }
}
