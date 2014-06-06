<?php slot('body') ?>
<p>ニュースレター登録を解除しました。<br/>解除完了確認メールを送信しました。</p>
<p>再登録を希望される場合は、<br/>もう一度ニュースレター登録を行ってください</p>
<?php end_slot() ?>
<?php op_include_box('NewsletterUnsubscribeComplete', get_slot('body'), array(
  'title' => 'ニュースレター登録解除',
)) ?>
