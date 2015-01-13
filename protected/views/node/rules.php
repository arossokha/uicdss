<?php

    if(empty($rules)) {
        echo "There is no rules!";
        return;
    }
    
    $headers = array_keys($rules[0]);
?>
<form method="post">
    <table>
        <thead>
            <tr>
                <?php
                foreach ($headers as $header) {
                    echo "<th>{$header}</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rules as $key => $rule) { ?>
            <tr>
                <?php foreach ($rule as $name => $val) { ?>
                <td>
                    <?php 
                        if(is_array($val) && array_key_exists('type', $val)) {
                            if(Param::TYPE_INPUT == $val['type'] ) {
                                echo $val['term'];
                            } elseif(Param::TYPE_OUTPUT == $val['type']) {
                                echo "<select name=\"rows[$key][{$name}]\">";
                                foreach ($val['terms'] as $k => $v) {
                                    if($val['term'] == $v) {
                                        $selected = ' selected="selected" ';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option $selected>{$v}</option>";
                                }
                                echo "</select>";
                            } else {
                                echo "1_WRONG DATA!!!".var_export($val,true);
                            }
                        } else {
                            echo "2_WRONG DATA!!!".var_export($val,true);
                        }
                    ?>
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="submit" value="Save" />
</form>
