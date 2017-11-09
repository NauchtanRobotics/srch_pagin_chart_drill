<!-- File: src/Template/Articles/index.ctp (delete links added) -->
<style>
    
    fieldset { margin:0; padding: 0; }
    ul { margin: 10px 0 0 0; }
    .partname { max-width: 150px; height:25px; margin-right:10px; padding-right: 5px; }
    .actions.columns { padding: 0 0 0 25px; margin: 0.2em 0 0 0; }
    .paginator { margin: 1em 0 1em 0; }
    p { font-size: 0.8em; }
    
</style>

<div class="actions columns large-2 medium-3">
    <h1>Parts</h1>

    <?= $this->Form->create(null, ['id' => 'target','valueSources' => 'query']) ?>
    <fieldset>
        <?php
            echo $this->Form->input('partname', array('class'=>'partname'));
        ?>
        <div class="input text">
            <label for="date_begin">Start Date</label>
            <input class="partname" type="text" id="date_begin" name="date_begin" value="<?php if(isset($this->request->query['date_begin'])){ echo $this->request->query['date_begin']; } ?>" >
        </div>
        <div class="input text">
            <label for="date_end">End Date (not included)</label>
            <input class="partname" type="text" id="date_end" name="date_end" value="<?php if(isset($this->request->query['date_end'])){ echo $this->request->query['date_end']; } ?>" >
        </div>
        <?= $this->Form->button('Filter', ['type' => 'submit']) //$this->Form->button(__('Search!')) ?>
    <?php 
    //if ($isSearch) {
        echo $this->Html->link('Reset', ['action' => 'index']);
    //}
    ?>
    </fieldset>
    
<!--?= $this->Html->link('Reset', ['action' => 'index']) ?-->
     <?= $this->Form->end() ?>
    <p><?= $number ?> Results found</p>
</div>



<div class="users index large-10 medium-9 columns">    
    
     <ul class="side-nav">
        <li><h3><?= $this->Html->link('Add Part', ['action' => 'add']) ?></h3></li>
     </ul>   

     <svg class="chart"></svg>
     
</div>
   


<div class="users index large-12 medium-12 columns">    

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
    </div>  
    <table>
        <tr>
            <th style="width:5%;"><?= $this->Paginator->sort('part_id','ID') ?></th>
            <th style="width:40%;"><?= $this->Paginator->sort('partname','Title') ?></th>
            <th style="width:15%;"><?= $this->Paginator->sort('created','Created') ?></th>
            <th style="width:15%;"><?= $this->Paginator->sort('modified','Modified') ?></th>
            <th style="width:10%;"></th>
            <th style="width:15%;"></th>
        </tr>

    <!-- Here's where we loop through our $articles query object, printing out article info -->

        <?php foreach ($parts as $part): ?>
        <tr>
            <td><?= $part->part_id ?></td>
            <td>
                <!--?= $part->partname ?-->
                <?= $this->Html->link($part->partname, ['action' => 'view', $part->part_id]) ?>
            </td>
            <td>
                <?= $part->created->i18nFormat('yyyy-MM-dd') ?>
            </td>
            <td>
                <?= $part->modified->i18nFormat('yyyy-MM-dd') ?>
            </td>
            <td>
                <?= $this->Html->link('Edit', ['action' => 'edit', $part->part_id]) ?>
            </td>
            <td>
                <?= $this->Form->postLink(
                    'Delete',
                    ['action' => 'delete', $part->part_id],
                    ['confirm' => 'Are you sure?'])
                ?>
            </td>
        </tr>
        <?php endforeach; ?>


    </table>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p>Page <?= $this->Paginator->counter() ?></p>
    </div>  
    

    
</div>


<?= $this->Html->css('chart') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://d3js.org/d3.v4.js" charset="utf-8"></script>
<!--script src="http://d3js.org/d3.v4.min.js"></script-->
<script>
    
    // parse the date / time
    var parseTime = d3.timeParse("%Y-%m-%d");
    var formatAsMonthYear = d3.timeFormat("%b-%Y");
    
    // Receive data from database controller and feed into javascript object
    var data = <?php echo json_encode($data); ?>;

    // Determine whether we are viewing all of the data (level 0) or are we zoomed into looking at a certain month (level 1)
    var level = <?php if(isset($this->request->query['date_end'])){ echo 1; } else { echo 0; } ?>;
    
    // Determin the start of the filtered period
    var firstMonth = formatAsMonthYear(parseTime('<?php if(isset($this->request->query['date_begin'])){ echo $this->request->query['date_begin']; } else { echo ""; } ?>'));
  
    // Determine the month according to the end date of the filtered period
    var nameOfMonth = formatAsMonthYear(parseTime('<?php if(isset($this->request->query['date_end'])){ echo $this->request->query['date_end']; } else { echo ""; } ?>')-1);
  
</script>
<?= $this->Html->script('hierarchicalbarchart') ?>