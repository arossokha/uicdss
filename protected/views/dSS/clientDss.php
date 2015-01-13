<h1>
    СППР "<?php echo $dss->name;?>"
</h1>
<br />
<?php
    foreach ($dss->nodes as $node) {
    ?>
    <div class="nodeBlock">
        <h2 title="<?php echo $node->description;?>"><?php echo $node->name;?></h2>
        <?php foreach ($node->params as $param) { ?>
        <div class="nodeParamBlock">
            <h3 title="<?php echo $param->description;?>" style="display:inline-block;" >
                <?php echo $param->name;?>
            </h3>
            <input type="number" name="" value="<?php echo $param->min;?>" 
                    min="<?php echo $param->min;?>" 
                    max="<?php echo $param->max;?>" <?php echo (in_array($param->primaryKey, $outputParamIds) ? ' disabled="disabled" readonly="readonly" ':''); ?>/>
            <br />
            <b>Min:</b><?php echo $param->min;?> 
            <b>Max:</b><?php echo $param->max;?>
            <hr />
        </div>
        <?php }?>
    </div>
<?php } ?>