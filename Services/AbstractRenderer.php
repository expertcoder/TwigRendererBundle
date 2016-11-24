<?php

namespace ExpertCoder\TwigRendererBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractRenderer
{
	use ContainerAwareTrait;

	/**
	 * @return \Twig_Template
	 */
	protected function getTemplateObject($path, $templateName)
	{
		return $this->getTwigEnvironment()->loadTemplate($path.'/'.$templateName);
	}

	protected function renderTwigBlock(\Twig_Template $templateObject, $block, $params = array())
	{
		$twig = $this->getTwigEnvironment();

		return $templateObject->renderBlock($block, $twig->mergeGlobals($params));
	}

	/**
	 * @return \Twig_Environment
	 */
	protected function getTwigEnvironment()
	{
		return $this->container->get('twig');
	}

	protected function checkTemplateHasBlock(\Twig_Template $templateObject, $requiredBlock)
	{
		if ($templateObject->hasBlock($requiredBlock) ) {
			return true;
		} else {
			return false;
		}
	}

	protected function renderView($view, array $parameters = array())
	{
		return $this->getTwigEnvironment()->render($view, $parameters);
	}

}