<?php

/**
 * @package     opNewsletterPlugin
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class newsletterActions extends sfActions
{
  public function executeSubscribe(opWebRequest $request)
  {
    $form = new opNewsletterSubscriptionForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form->bind($request[$form->getName()]);
      if ($form->isValid())
      {
        $form->doSubscribe();
        return sfView::SUCCESS;
      }
    }

    $this->form = $form;

    return sfView::INPUT;
  }

  public function executeUnsubscribe(opWebRequest $request)
  {
    $form = new opNewsletterSubscriptionForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form->bind($request[$form->getName()]);
      if ($form->isValid())
      {
        $form->doUnsubscribe();
        return sfView::SUCCESS;
      }
    }

    $this->form = $form;

    return sfView::INPUT;
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
