
<!-- <?php
$this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/styles/table.css');
  ?> -->


  <style type="text/css">
  #global_wrapper{
    background-color: #fcfcfc !important;
    border-top: 1px solid gray;
}

table{
    /*border: 1px solid black;*/
    width: 100%;

    border-radius: 25px;
}

.table-heading{
    background-color: #f5f5f5;
}
th{
    font-size: 18px;
    font
}
th,td{
    padding: 10px;
    color: black;
}
tr:nth-child(even) {
    background-color: white;
}
.item-row{
    border-bottom: 1px solid #fae1e1;
}


</style>

<?php


$paginator = $this->paginator;



if ($paginator->getTotalItemCount() > 0) :
    ?>





    <table class="admin_table table_auto">

        <thead class="table-heading">
            <tr class="heading-row">
                <th>Date</th>
                <th>Invoice</th>
                <th>Recipient</th>
                <th>Type</th>
                <th>Creator</th>
                <th>Action</th>
                <th>Amount</th>

            </tr>

        </thead>


        


        <tbody>

            <?php foreach ($paginator as $item) : ?>

                <?php
                $type = "unpaid";
                if($item['type'] == 1) $type = "paid";
                ?>

                <tr class="item-row">
                    <td><?=$item['creation_date']?></td>
                    <td><?=$item['invoice_number']?></td>
                    <td><?=$item['customer_name']?></td>
                    <td><?=$type?></td>
                    <td><?=$item['creator_name']?></td>
                    <td>
                        <span>
                            <?php echo $this->htmlLink(array(
                                'action'=>'edit',
                                'invoice_id'=> $item->getIdentity(),
                                'route'=> 'invoice_specific',
                                'reset'=> true,
                            ),'edit'); ?>
                        </span>
                        |
                        <span>
                            <?php
                            echo $this->htmlLink(
                                array(
                                    'route' => 'default',
                                    'module' => 'invoice',
                                    'controller' => 'index',
                                    'action' => 'delete',
                                    'invoice_id' => $item->getIdentity(),
                                    'format' => 'smoothbox',
                                ),
                                "delete",array(
                                    'class'=>'buttonlink smoothbox'));
                            ?>
                        </span>

                        |

                        <span>
                            <?php echo $this->htmlLink(array(
                                'action'=>'view',
                                'invoice_id'=> $item->getIdentity(),
                                'route'=> 'invoice_specific',
                                'reset'=> true,
                            ),'view'); ?>
                        </span>
                        <td><?=$item['total'];?></td>
                    </tr>
                <?php endforeach; ?>



            </tbody>

        </table>


    <?php endif; ?>

    <?php echo $this->paginationControl($this->paginator, null, null, array(
        'pageAsQuery' => true,
        'query' => $this->formValues,
    //'params' => $this->formValues,
        )); ?>