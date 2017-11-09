<!-- File: src/Template/Articles/view.ctp -->

<h1><?= h($part->partname) ?></h1>
<p>Created: <?= $part->created->format(DATE_RFC850) ?></p>
<p>Modified: <?= $part->modified->format(DATE_RFC850) ?></p>
