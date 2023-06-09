<?php

// app/graphql/types/CategoryType

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Collection of categories',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'name' => Type::nonNull(Type::string()),
                'description' => 'ID of quest'
            ],
            // 'title' => [
            //     'name' => Type::nonNull(Type::string()),
            //     'description' => 'Title of the quest'
            // ],
            // 'quests' => [
            //     'name' => Type::listOf(GraphQL::type('Quest')),
            //     'description' => 'List of quests'
            // ]
        ];
    }
}
