<?php

namespace nthmedia\entrygpscoordinates\graphql;

use craft\gql\base\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class EntryCoordinatesFieldResolver extends ObjectType
{
    /**
     * @inheritdoc
     */
    protected function resolve($source, $arguments, $context, ResolveInfo $resolveInfo)
    {
        $fieldName = $resolveInfo->fieldName;
        return $source->$fieldName;
    }
}
