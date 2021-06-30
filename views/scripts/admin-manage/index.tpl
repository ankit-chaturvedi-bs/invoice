<script type="text/javascript">

    function multiDelete()
    {
      return confirm("<?php echo $this->translate('Are you sure you want to delete the selected blog entries?');?>");
  }

  function selectAll()
  {
      var i;
      var multidelete_form = $('multidelete_form');
      var inputs = multidelete_form.elements;
      for (i = 1; i < inputs.length; i++) {
        if (!inputs[i].disabled) {
          inputs[i].checked = inputs[0].checked;
      }
  }
}
</script>




<?php if( count($this->navigation) ): ?>
    <div class='tabs'>
        <?php
    // Render the menu
    //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
    </div>
<?php endif; ?>


<?php


$paginator = $this->paginator;

?>

<style type="text/css">
    .search{
        margin-bottom: 6px;
    }

</style>

 <div class="admin_form" >

    <?php echo $this->form->render($this) ?>


</div> 


<div >
<?php if( count($this->paginator) ): ?>
    <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
        <table class='admin_table'>
          <thead>
            <tr>
              <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
              <th class='admin_table_short'>ID</th>
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
           <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity(); ?>' value="<?php echo $item->getIdentity(); ?>" /></td>
           <td><?php echo $item->getIdentity() ?></td>
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
                        'action'=>'delete',
                        'module'=> 'invoice',
                        'controller'=>'admin-manage',
                        'id'=> $item->getIdentity(),
                        'route'=> 'default',
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
                    <td>
                        <?=$item['total']?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <br />

    <div class='buttons'>
      <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
  </div>
</form>


<?php else: ?>
    <div>
        <img src="<?=$this->layout()->staticBaseUrl?>application/modules/Invoice/externals/images/no_data.png" >
    </div>

</div>

<?php endif; ?>




<?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    //'params' => $this->formValues,
    )); ?>