<?php

/**
 * @package     opNewsletterPlugin
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class newsletterActions extends sfActions
{
  public function executeSend(opWebRequest $request)
  {
    $form = new opNewsletterSendForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $form->bind($request[$form->getName()]);
      if ($form->isValid())
      {
        $form->sendMail();
        $this->getUser()->setFlash('notice', '配信完了しました。');
        $this->redirect(array('sf_route' => 'newsletter_send'));
      }
    }

    $this->form = $form;

    return sfView::INPUT;
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
