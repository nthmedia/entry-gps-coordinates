<?php

namespace nthmedia\entrygpscoordinates\graphql;

use craft\gql\base\GeneratorInterface;
use craft\gql\GqlEntityRegistry;
use craft\gql\TypeLoader;
use GraphQL\Type\Definition\Type;
use nthmedia\entrygpscoordinates\fields\EntryCoordinates;

class EntryCoordinatesFieldTypeGenerator implements GeneratorInterface
{
    /**
     * @inheritdoc
     */
    public static function generateTypes($context = null): array
    {
        /** @var EntryCoordinates $context */
        $typeName = self::getName($context);
        $props = [
            'searchQuery' => Type::string(),
            'coordinates' => Type::string(),
            'latitude' => Type::float(),
            'longitude' => Type::float(),
        ];

        $coordinatesProperty =  GqlEntityRegistry::getEntity($typeName)
            ?: GqlEntityRegistry::createEntity($typeName, new EntryCoordinatesFieldResolver([
            'name' => $typeName,
            'description' => 'This entity has all the EntryCoordinates properties',
            'fields' => function () use ($props) {
                return $props;
            }
        ]));

        TypeLoader::registerType($typeName, function () use ($coordinatesProperty) {
            return $coordinatesProperty;
        });

        return [$coordinatesProperty];
    }

    /**
     * @inheritdoc
     */
    public static function getName($context = null): string
    {
        /** @var EntryCoordinates $context */
        return $context->handle . '_EntryCoordinateField';
    }
}
