<?php

/**
 * @package     opNewsletterPlugin
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class newsletterActions extends sfActions
{
  public function executeIndex(opWebRequest $request)
  {
    $this->redirect(array('sf_route' => 'newsletter_send'));
  }

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

  public function executeSubscriberManage(opWebRequest $request)
  {
    $form = new opNewsletterSubscriberManageForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $remove = isset($request['submit_remove']);
      if (!$remove)
      {
        $form->setAddLimitValidator();
      }
      else
      {
        $form->setRemoveLimitValidator();
      }

      $form->bind($request[$form->getName()]);
      if ($form->isValid())
      {
        if (!$remove)
        {
          $form->doSubscribeAll();
          $this->getUser()->setFlash('notice', '登録完了しました。');
        }
        else
        {
          $form->doUnsubscribeAll();
          $this->getUser()->setFlash('notice', '登録解除完了しました。');
        }

        $this->redirect(array('sf_route' => 'newsletter_subscriberManage'));
      }
    }

    $this->form = $form;

    return sfView::INPUT;
  }
}
// vim: et fenc=utf-8 sts=2 sw=2 ts=2
