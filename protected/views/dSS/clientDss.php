<h1>
    СППР "<?php echo $dss->name;?>"
</h1>
<br />
<form method="post">
    <div class="nodeList">
    <?php
        foreach ($dss->nodes as $nodeNum => $node) {
            if(($nodeNum % 5) == 0 && $nodeNum != 0) {
                ?>
                </div>
                <div class="nodeList">
                <?php
            }
        ?>
            <div class="nodeBlock">
                <h2 title="<?php echo $node->description;?>"><?php echo $node->name;?></h2>
                <input type="hidden" name="nodeId[<?php echo $nodeNum;?>]" value="<?php echo $node->primaryKey;?>">
                <?php foreach ($node->params as $param) { ?>
                <div class="nodeParamBlock">
                    <h3 title="<?php echo $param->description;?>" style="display:inline-block;" >
                        <?php echo $param->name;?>
                    </h3>
                    <input type="hidden" name="paramId[<?php echo $nodeNum;?>][]" value="<?php echo $param->primaryKey;?>">
                    <input type="number" name="paramValue[<?php echo $nodeNum;?>][]" value="<?php echo $param->min;?>" 
                            min="<?php echo $param->min;?>" 
                            max="<?php echo $param->max;?>" <?php echo (in_array($param->primaryKey, $outputParamIds) ? ' disabled="disabled" readonly="readonly" ':''); ?>/>
                    <br />
                    <b>Min:</b><?php echo $param->min;?> 
                    <b>Max:</b><?php echo $param->max;?>
                    <b>Terms:</b><?php echo $param->term->names;?>
                    <hr />
                </div>
                <?php }?>
            </div>
    <?php } ?>
    </div>
    <div class="clear"></div>
    <input type="submit" value="Calculate" />
</form>
<?php

if($results) {
    var_dump($skipedNodes);
    foreach ($skipedNodes as $nodeId => $nodeData) {
        foreach ($nodeData['errors'] as $fieldName => $errors) {
            if('rulesTable' == $fieldName) {
                echo CHtml::link("Set rules table for node: <b>{$nodeData[name]}</b>",array('/node/rules','nodeId' => $nodeId)).'<br />';
            } else {
                echo 'Unknown errors '.implode(';',$errors).'<br />';
            }
        }
    }

    CVarDumper::dump($results,5,true);
}

?>