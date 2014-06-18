<style type="text/css">
<!--
#newsletter_subscriber_form {
  width: 700px;
}

#newsletter_subscriber_mail_address,
#newsletter_subscriber_submit_add,
#newsletter_subscriber_submit_remove {
          box-sizing: border-box;
     -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;

  display: block;
}

#newsletter_subscriber_mail_address {
  width: 100%;
  height: 400px;
}

#newsletter_subscriber_submit_group {
  overflow: hidden;
}

#newsletter_subscriber_submit_add,
#newsletter_subscriber_submit_remove {
  width: 50%;
  height: 40px;
  float: left;
  margin-top: 10px;
}
-->
</style>

<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2>ニュースレターアドレス追加・削除</h2>

<?php echo $form->renderFormTag(url_for('@newsletter_subscriberManage'), array('method' => 'POST', 'id' => 'newsletter_subscriber_form')) ?>
  <?php echo $form->renderHiddenFields() ?>
  <?php echo $form->renderGlobalErrors() ?>

  <?php echo $form['mail_address']->renderError() ?>
  <?php echo $form['mail_address']->render(array('placeholder' => 'メールアドレス (一行ずつ改行)')) ?>

  <p>&#8251;登録・解除の操作実行後、対象のメールアドレス宛にそれぞれ登録・解除の案内メールを送信します。</p>

  <div id="newsletter_subscriber_submit_group">
    <input type="submit" id="newsletter_subscriber_submit_add" name="submit_add" value="ニュースレターアドレス追加"/>
    <input type="submit" id="newsletter_subscriber_submit_remove" name="submit_remove" value="入力したアドレスを削除"/>
  </div>
</form>

<?php include_component('newsletter', 'subscriberList') ?>
