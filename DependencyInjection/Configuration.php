<?php

namespace ExpertCoder\TwigRendererBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('expert_coder_twig_renderer');

		$rootNode->isRequired()->cannotBeEmpty()
			->children()
				->scalarNode('default_from_email')
					->isRequired()
					->cannotBeEmpty()
				->end()
				->scalarNode('default_from_name')
					->isRequired()
					->cannotBeEmpty()
				->end()
			->end()
		;

        return $treeBuilder;
    }
}
