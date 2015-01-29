<?php foreach($list as $v) : ?>
<p><?php echo CHtml::link($v, array('/memCache/view', 'name'=>$v), array('target'=>'_blank')); ?></p>
<?php endforeach; ?>