<?php

namespace ExpertCoder\TwigRendererBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractRenderer
{
	use ContainerAwareTrait;

	/**
	 * @return \Twig_Template
	 */
	protected function getTemplateObject($templatePath)
	{
		return $this->getTwigEnvironment()->loadTemplate($templatePath);
	}

	protected function renderTwigBlock(\Twig_Template $templateObject, $block, $params = array())
	{
		$twig = $this->getTwigEnvironment();

		try {
			$renderedContents = $templateObject->renderBlock($block, $twig->mergeGlobals($params));
		} catch (\Twig_Error $e) {
			//Slight work around Twig_Template::renderBlock() is using output buffering
			//So need to tidy that up if there is an exception
			ob_clean();
			throw $e;
		}

		return $renderedContents;
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