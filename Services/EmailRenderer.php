<?php

namespace ExpertCoder\TwigRendererBundle\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailRenderer extends AbstractRenderer
{
	protected $options;

	public function sendEmail(array $options = array())
	{
		$resolver = new OptionsResolver();
		$this->configureOptionsForSendEmail($resolver);
		$this->options = $resolver->resolve($options);

		$templateObject = $this->getTemplateObject($this->options['templatePath']);

		$this->checkTemplateHasRequiredBlocksForEmail($templateObject);

		$body = $this->renderTwigBlock($templateObject, 'body', $this->options['templateParams']);
		$subject = $this->renderTwigBlock($templateObject, 'subject', $this->options['templateParams']);

		$message = $this->getSwiftMailer()->createMessage();

		$message->setTo($this->options['to']);
		$message->setFrom($this->options['from']);
		$message->setSubject($subject);
		$message->setBody($body, 'text/html');

		/*
		if (isset($this->options['attachmentFileName']) ) {

			//NOTE: rethink hard coded path here.
			$filePath = $this->container->get('kernel')->getRootDir().'/Resources/views/Emails/attachments/'.$this->options['attachmentFileName'];

			$attachment = \Swift_Attachment::fromPath($filePath);
			$message->attach($attachment);
		} */

		$this->getSwiftMailer()->send($message);
	}

	protected function checkTemplateHasRequiredBlocksForEmail(\Twig_Template $templateObject)
	{
		foreach (array('subject', 'body') as $requiredBlock) {
			if (!  $this->checkTemplateHasBlock($templateObject, $requiredBlock) ) {
				throw new \Exception('Email template is missing the block "'.$requiredBlock.'"');
			}
		}
	}

	protected function configureOptionsForSendEmail(OptionsResolver $resolver)
	{
		$defaultFromName = $this->container->getParameter('expert_coder_twig_renderer.default_from_name');
		$defaultFromEmail = $this->container->getParameter('expert_coder_twig_renderer.default_from_email');

		$resolver->setDefaults(array(
			'from'   => array($defaultFromEmail => $defaultFromName ),
			'templateParams' => array(),
		));

		$resolver->setRequired(array(
			'to',
			'templatePath',
		));

		$resolver->setDefined(array(
			'attachmentFileName'
		));

		$resolver->setAllowedTypes('templateParams', ['array']);
	}

	/**
	 * @return \Swift_Mailer
	 */
	protected function getSwiftMailer()
	{
		return $this->container->get('swiftmailer.mailer');
	}

}