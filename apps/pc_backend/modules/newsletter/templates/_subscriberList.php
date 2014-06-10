<style type="text/css">
<!--
.subscriber_list {
  width: 700px;
  margin: 15px 0;

  overflow: hidden;
}

.subscriber_list > li {
  float: left;
  width: 200px;
  margin-left: 25px;
}
-->
</style>

<ul class="subscriber_list">
  <?php foreach ($subscribers as $subscriber): ?>
  <li><?php echo $subscriber->mail_address ?></li>
  <?php endforeach ?>
</ul>
