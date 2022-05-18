<?php

namespace CoreShop\Bundle\WishlistBundle\DependencyInjection;

use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Bundle\WishlistBundle\Controller\WishlistController;
use CoreShop\Bundle\WishlistBundle\Pimcore\Repository\WishlistItemRepository;
use CoreShop\Bundle\WishlistBundle\Pimcore\Repository\WishlistRepository;
use CoreShop\Component\Resource\Factory\PimcoreFactory;
use CoreShop\Component\Wishlist\Model\WishlistInterface;
use CoreShop\Component\Wishlist\Model\WishlistItemInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('coreshop_wishlist');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->booleanNode('legacy_serialization')->defaultTrue()->end()
            ->end();
        $this->addModelsSection($rootNode);
        $this->addStack($rootNode);

        return $treeBuilder;
    }

    private function addStack(ArrayNodeDefinition $node): void
    {
        $node->children()
            ->arrayNode('stack')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('wishlist')->defaultValue(WishlistInterface::class)->cannotBeEmpty()->end()
                    ->scalarNode('wishlist_item')->defaultValue(WishlistItemInterface::class)->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();
    }

    private function addModelsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('pimcore')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('wishlist')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('path')
                                    ->children()
                                        ->scalarNode('wishlist')->defaultValue('wishlists')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopWishlist')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(WishlistInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(WishlistRepository::class)->end()
                                        ->scalarNode('install_file')->defaultValue('@CoreShopWishlistBundle/Resources/install/pimcore/classes/CoreShopWishlist.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                        ->arrayNode('pimcore_controller')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(WishlistController::class)->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('wishlist_item')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->scalarNode('path')->defaultValue('items')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopWishlistItem')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(WishlistItemInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(WishlistItemRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('install_file')->defaultValue('@CoreShopWishlistBundle/Resources/install/pimcore/classes/CoreShopWishlistItem.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
