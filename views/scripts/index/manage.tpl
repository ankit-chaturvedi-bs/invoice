<?php


$paginator = $this->paginator;



if ($paginator->getTotalItemCount() > 0) :
?>

<ul class="invoice-list">

    <?php foreach ($paginator as $item) : ?>
    <li class="invoice-list-item">
        <?php  
        echo $item['invoice_number'];
        ?>
        

    
    </li>
    <?php endforeach; ?>
</ul>

<?php endif; ?>

<?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    //'params' => $this->formValues,
)); ?>