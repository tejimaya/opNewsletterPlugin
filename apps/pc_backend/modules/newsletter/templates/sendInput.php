<style type="text/css">
<!--
#newsletter_form {
  width: 700px;
}

#newsletter_subject, #newsletter_body, #newsletter_submit {
          box-sizing: border-box;
     -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;

  display: block;
  width: 100%;
}

#newsletter_subject {
  font-size: 140%;
}

#newsletter_body {
  height: 400px;
}

#newsletter_submit {
  height: 40px;
  margin-top: 10px;
}
-->
</style>

<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2>ニュースレター配信</h2>

<?php echo $form->renderFormTag(url_for('@newsletter_send'), array('method' => 'POST', 'id' => 'newsletter_form')) ?>
  <?php echo $form->renderHiddenFields() ?>
  <?php echo $form->renderGlobalErrors() ?>

  <?php echo $form['subject']->render(array('placeholder' => '件名')) ?>
  <?php echo $form['body']->render(array('placeholder' => '本文')) ?>

  <input type="submit" id="newsletter_submit" value="ニュースレターを配信する"/>
</form>

<?php include_component('newsletter', 'subscriberList') ?>
