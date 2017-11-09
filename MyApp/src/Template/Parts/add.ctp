<!-- File: src/Template/Articles/add.ctp -->

<h1>Add Article</h1>
<?php
    echo $this->Form->create($part);
    echo $this->Form->control('partname');
   // echo $this->Form->control('body', ['rows' => '3']);
    echo $this->Form->button(__('Save Part'));
    echo $this->Form->end();
?>
