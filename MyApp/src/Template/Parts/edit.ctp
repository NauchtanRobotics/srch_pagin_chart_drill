<!-- File: src/Template/Articles/edit.ctp -->

<h1>Edit Part</h1>
<?php
    echo $this->Form->create($part);
    echo $this->Form->control('partname');
    echo $this->Form->button(__('Save Part'));
    echo $this->Form->end();
?>
