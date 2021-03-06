<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FollowerMutator
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $user = \App\User::find($args['following_id']);
        $followers = $user->followers()->get();
        foreach($followers as $follow){
            if($follow->id == $context->user()->id){
                $context->user()->followings()->detach($user);
                return $context->user();
                
            }
        }
        
        
        $context->user()->followings()->attach($user);

        
        // $user->followers()->attach($context->user());
        return $user;
    }
    public function destroy($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $user = \App\User::find($args['following_id']);
        $context->user()->followings()->detach($user);
        $user->followers()->detach($context->user());
        return $user;
    }
}
