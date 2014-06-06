<?php slot('body') ?>
<p>登録完了しました。</p>
<p>登録完了メールを送信しています。<br/>確認メールが届かない場合は、アドレスが間違っているか迷惑メール設定されている可能性があります。</p>
<p>アドレスを確認し、もう一度再登録してください。</p>
<?php end_slot() ?>
<?php op_include_box('NewsletterSubscribeComplete', get_slot('body'), array(
  'title' => 'ニュースレター登録',
)) ?>
