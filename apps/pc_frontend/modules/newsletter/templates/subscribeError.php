<?php slot('body') ?>
<p>ニュースレター配信登録数が予定数に達したため、現在登録を停止しております。</p>
<?php end_slot() ?>
<?php op_include_box('NewsletterSubscribeComplete', get_slot('body'), array(
  'title' => 'ニュースレター登録',
)) ?>
