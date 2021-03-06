<?php
/**
 *    Autopsy importation tool
 *  Samples Screen
 *  VERSION:1.0.0
 *  AUTHOR:DE WINTER JOHAN
 *  CREATED:20/04/2010
 *  LAST MODIFIED:20/04/2010
 */

include_once(Classes . 'import/flow_class.php');

include_once(Functions . 'Fixcoding.php');


// Database Access

$db = $this->db;

//  Set javascript & css files, to be loaded dynamically 
$css = "/legacy/css/autopsy_import/autopsy_samples.css";

$js = "/legacy/js/autopsy_import/autopsy_samples.js";


$val = $this->validation;

$val->setStatus(true);

$necropsy_seqno = $this->getThread();


// get lesion types

$sql = "select seqno,ogn_code,processus from lesion_types";
$res = $db->query($sql);
$lesion_types = array();
if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
} else {
    while ($row = $res->fetch()) {
        $lesion_types[$row['OGN_CODE'] . '/' . $row['PROCESSUS']] = array('seqno' => $row['SEQNO'],
            'ogn_code' => $row['OGN_CODE'],
            'processus' => $row['PROCESSUS']);
    }
}


// get organ lesions for this necropsy

$sql = "select  b.processus, b.ogn_code,a.seqno as oln_seqno from organ_lesions a, lesion_types b where a.lte_seqno = b.seqno and ncy_ese_seqno = '$necropsy_seqno'";
$res = $db->query($sql);
$registered_organ_lesion = array();
if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
} else {
    while ($row = $res->fetch()) {
        if (isset($row['OGN_CODE']) && isset($row['PROCESSUS'])) {
            $registered_organ_lesion[$row['OLN_SEQNO']] = $row['OGN_CODE'] . "/" . $row['PROCESSUS'];
        }
    }
}

// get registered lesion samples previously to any change

$sql = "SELECT c.ogn_code,
  c.processus,
  b.ncy_ese_seqno,
  d.availability,
  d.conservation_mode,
  d.analyze_dest,
  d.spe_type,
  d.seqno
FROM lesions2sample a,
  organ_lesions b,
  lesion_types c,
  samples d
where a.oln_seqno = b.seqno
AND c.seqno              = b.lte_seqno
AND d.seqno              = a.spe_seqno
AND b.ncy_ese_seqno      = :seqno";
$bind = array(':seqno' => $necropsy_seqno);

$res = $db->query($sql, $bind);

$registered_samples = array();

if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
} else {
    while ($row = $res->fetch()) {
        if (isset($row['OGN_CODE']) && isset($row['PROCESSUS'])) {
            $registered_samples[$row['SEQNO']] = $row;
        }
    }
}


// CRUD on samples
if ($this->isSubmitted() && $val->getStatus()) // something has been submitted and no error is observed
{
    isset($_POST['organlesionsample']) ? $organlesionssamples = $_POST['organlesionsample'] : $organlesionssamples = '';
    $organlesionssamples = is_array($organlesionssamples) ? $organlesionssamples : array();
    foreach ($organlesionssamples as $organlesionsample) {
        $lesionsample = json_decode($organlesionsample, true);
        $lesion = $lesionsample["lesion"];
        $lesion_type = $lesion_types[$lesion[0] . "/" . $lesion[1]];
        // in case the sample is to be created
        $oln_seqno = array_search($lesion[0] . "/" . $lesion[1], $registered_organ_lesion);
        if ($oln_seqno === false) {
            $oln_seqno = null;
        }
        // in case of an insert
        if (!isset($lesionsample['SEQNO'])) {
            //$oln_seqno = null;

            // the only organ lesions that would need to be created are related to 'NA'

            // if the organ lesion doesn't exist then create it
            if ($oln_seqno === null && $lesion[1] == 'NA') {
                //the lesion is not previously defined

                $sql = "insert into organ_lesions(lte_seqno,ncy_ese_seqno,scale) values(:lte_seqno,:ncy_seqno,'1') ";
                $binds = array(':lte_seqno' => $lesion_type['seqno'], ':ncy_seqno' => $necropsy_seqno);
                $res = $db->query($sql, $binds);
                if ($res->isError()) {
                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                } else {
                    $oln_seqno = $db->query("select organ_lesions_seq.currval from dual")->fetch()['CURRVAL'];
                    $registered_organ_lesion[$oln_seqno] = $lesion[0] . "/" . $lesion[1]; //update registered organ lesion

                }
            }
            // insert sample into database
            $sql = "insert into samples(availability,conservation_mode,analyze_dest,spe_type) values (:availability,:cvt_mode,:an_dest,:spe_type)";
            $binds = array(':availability' => $lesionsample['AVAILABILITY'], ':cvt_mode' => $lesionsample['CONSERVATION_MODE'],
                ':an_dest' => $lesionsample['ANALYZE_DEST'],
                ':spe_type' => $lesionsample['SPE_TYPE']);

            $res = $db->query($sql, $binds);

            if ($res->isError()) {
                $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
            } elseif ($oln_seqno !== null) {
                // get just created sample seqno
                $sample_seqno = $db->query("select samples_seq.currval from dual")->fetch()['CURRVAL'];

                // link sample to organlesion

                $sql = "insert into lesions2sample(spe_seqno,oln_seqno) values (:spe_seqno,:oln_seqno)";
                $binds = array(':spe_seqno' => $sample_seqno, ':oln_seqno' => $oln_seqno);

                $res = $db->query($sql, $binds);
                if ($res->isError()) {
                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                }
            }
        } else {
            // in case of a delete: actually delete it; availability is another matter 
            if (isset($lesionsample['SEQNO']) && isset($lesionsample['DEL']) && $lesionsample['DEL'] == 'TRUE') {
                $sql = "delete from lesions2sample where spe_seqno = :spe_seqno and oln_seqno = :oln_seqno";
                $binds = array(':spe_seqno' => $lesionsample['SEQNO'], ':oln_seqno' => $oln_seqno);
                $res = $db->query($sql, $binds);
                if ($res->isError()) {
                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                }

                $sql = "delete from samples where seqno = :seqno";
                $binds = array(':seqno' => $lesionsample['SEQNO']);
                $res = $db->query($sql, $binds);
                if ($res->isError()) {
                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                }
            }

            // in case of an update
            if (isset($lesionsample['SEQNO']) && isset($lesionsample['UPD']) && $lesionsample['UPD'] == 'TRUE' && (!isset($lesionsample['DEL']) || $lesionsample['DEL'] == 'FALSE')) {
                // update only if previously registered
                if (array_key_exists($lesionsample['SEQNO'], $registered_samples)) {
                    // Update Sample
                    $old_sample = $registered_samples[$lesionsample['SEQNO']];

                    $toupdate = array();
                    $old_sample['ANALYZE_DEST'] != $lesionsample['ANALYZE_DEST'] ? $toupdate['ANALYZE_DEST'] = $lesionsample['ANALYZE_DEST'] : "";
                    $old_sample['CONSERVATION_MODE'] != $lesionsample['CONSERVATION_MODE'] ? $toupdate['CONSERVATION_MODE'] = $lesionsample['CONSERVATION_MODE'] : "";
                    $old_sample['SPE_TYPE'] != $lesionsample['SPE_TYPE'] ? $toupdate['SPE_TYPE'] = $lesionsample['SPE_TYPE'] : "";
                    $old_sample['AVAILABILITY'] != $lesionsample['AVAILABILITY'] ? $toupdate['AVAILABILITY'] = $lesionsample['AVAILABILITY'] : "";

                    if (count($toupdate) > 0) {
                        $toupdate_array = array();
                        $toupdate_string = "";
                        $binds = array();
                        foreach ($toupdate as $key => $valupd) {
                            $toupdate_array[] = " $key = :$key";
                            $binds[":$key"] = $valupd;
                        }
                        $toupdate_string = implode(',', $toupdate_array);
                        $sql = "update samples set $toupdate_string where seqno = :seqno";
                        $binds[':seqno'] = $lesionsample['SEQNO'];

                        $res = $db->query($sql, $binds);
                        if ($res->isError()) {
                            echo $res->errormessage() . '; ' . $sql;
                        }
                    }

                    // check if an organ (=lesion type actually) update is neccessary
                    if ($lesion[0] != $old_sample['OGN_CODE'] || $lesion[1] != $old_sample['PROCESSUS']) {
                        $new_lesion_type = $lesion_types[$lesion[0] . "/" . $lesion[1]]['seqno'];
                        $lesion_type_to_be_updated = $lesion_types[$old_sample['OGN_CODE'] . "/" . $old_sample['PROCESSUS']]['seqno'];

                        $oln_seqno = array_search($lesion[0] . "/" . $lesion[1], $registered_organ_lesion);
                        if ($oln_seqno === false) {
                            $oln_seqno = null;
                        }
                        //test if there aren't any lesion values attached to it
                        $sql = "select 1 from lesion_values lv right join organ_lesions ol on lv.oln_seqno=ol.seqno where ol.ncy_ese_seqno = :necropsy_seqno and ol.lte_seqno = :old_lte_seqno and lv.value is not null";
                        $binds = array(
                            ':necropsy_seqno' => intval($necropsy_seqno),
                            ':old_lte_seqno' => intval($lesion_type_to_be_updated));
                        $res = $db->query($sql, $binds);
                        $r = $res->fetch();
                        if ($res->isError()) {
                            $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                        } elseif ($r) {
                            $val->setError('globalerror', "The lesion value you tried to change ('".$lesion_types[$old_sample['OGN_CODE'] . "/" . $old_sample['PROCESSUS']]."') has values attached to it. Therefore it can't be changed.");
                        } else { //the organ_lesion has no values attached

                            //if the new lesion type isn't yet occurring as an organ lesion, use the combination
                            if ($oln_seqno === null) {

                                $sql = "update organ_lesions set lte_seqno = :new_lte_seqno where ncy_ese_seqno = :necropsy_seqno and lte_seqno = :old_lte_seqno";
                                $binds = array(
                                    ':new_lte_seqno' => intval($new_lesion_type),
                                    ':necropsy_seqno' => intval($necropsy_seqno),
                                    ':old_lte_seqno' => intval($lesion_type_to_be_updated));
                                $res = $db->query($sql, $binds);
                                if ($res->isError()) {
                                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                                } else {
                                    $registered_organ_lesion[$oln_seqno] = $lesion[0] . "/" . $lesion[1]; //update registered organ lesion
                                }

                                $sql = "update organ_lesions set lte_seqno = :new_lte_seqno where ncy_ese_seqno = :necropsy_seqno and lte_seqno = :old_lte_seqno";
                                $binds = array(
                                    ':new_lte_seqno' => intval($new_lesion_type),
                                    ':necropsy_seqno' => intval($necropsy_seqno),
                                    ':old_lte_seqno' => intval($lesion_type_to_be_updated));
                                $res = $db->query($sql, $binds);
                                if ($res->isError()) {
                                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                                } else {
                                    $registered_organ_lesion[$oln_seqno] = $lesion[0] . "/" . $lesion[1]; //update registered organ lesion
                                }
                            } else {
                                //if the new lesion type actually already exists, assign it to it (this means the lesion type has more than one sample)
                                $existing_lesion_type = $lesion_types[$lesion[0] . "/" . $lesion[1]];
                                $sql = "update organ_lesions set lte_seqno = :new_lte_seqno where ncy_ese_seqno = :necropsy_seqno and lte_seqno = :old_lte_seqno";
                                $binds = array(
                                    ':new_lte_seqno' => intval($existing_lesion_type),
                                    ':necropsy_seqno' => intval($necropsy_seqno),
                                    ':old_lte_seqno' => intval($lesion_type_to_be_updated));
                                $res = $db->query($sql, $binds);
                                if ($res->isError()) {
                                    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    if ($val->getStatus()) {
        $this->navigate();
        return;
    }
}


// get already registered lesion samples

$sql = "SELECT c.ogn_code,
  c.processus,
  b.ncy_ese_seqno,
  d.availability,
  d.conservation_mode,
  d.analyze_dest,
  d.spe_type,
  d.seqno
FROM lesions2sample a,
  organ_lesions b,
  lesion_types c,
  samples d
where a.oln_seqno = b.seqno
AND c.seqno              = b.lte_seqno
AND d.seqno              = a.spe_seqno
AND b.ncy_ese_seqno      = :seqno";
$bind = array(':seqno' => $necropsy_seqno);

$res = $db->query($sql, $bind);

$registered_samples = array();

if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
} else {
    while ($row = $res->fetch()) {
        if (isset($row['OGN_CODE']) && isset($row['PROCESSUS'])) {
            $registered_samples[$row['OGN_CODE'] . "/" . $row['PROCESSUS']][$row['SEQNO']] = $row;
        }
    }
}


// get Sample type
$sample_type_list = array();
$sql = "select rv_low_value,rv_meaning from cg_ref_codes where rv_domain = 'SAMPLE_TYPE'";
$res = $db->query($sql);
if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
}
$sample_type = "<select class='SampleType minwidth'><option value=''>Type...</option>";
while ($row = $res->fetch()) {
    //$selected = $row['RV_LOW_VALUE'] == 'ORG' ? "selected='selected'" : "";
    $sample_type .= "<option value='" . $row['RV_LOW_VALUE'] . "'>" . $row['RV_MEANING'] . "</option>";
    $sample_type_list[$row['RV_LOW_VALUE']] = $row['RV_MEANING'];
}
$sample_type .= "</select>";

// get Conservation mode
$conservation_mode_list = array();
$sql = "select rv_low_value,rv_meaning from cg_ref_codes where rv_domain='CONSERVATION_MODE'";
$res = $db->query($sql);
if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
}
$conservation_mode_body = "<select class='CsvModeBody minwidth'><option value=''>Csv mode...</option>";
while ($row = $res->fetch()) {
    $conservation_mode_body .= "<option value='" . $row['RV_LOW_VALUE'] . "'>" . $row['RV_MEANING'] . "</option>";
    $conservation_mode_list[$row['RV_LOW_VALUE']] = $row['RV_MEANING'];
}
$conservation_mode_body .= "</select>";

// get Analyze_dest 
$avandest = array();
$analyze_dest = array();
$default_conservation_mode = array();
$sql = "select rv_low_value,rv_meaning,rv_high_value from cg_ref_codes where rv_domain='ANALYZE_DEST'";
$res = $db->query($sql);
if ($res->isError()) {
    echo $res->errormessage() . '; ' . $sql;
}
while ($row = $res->fetch()) {
    $analyze_dest[$row['RV_LOW_VALUE']] = $row['RV_MEANING'];
    $avandest[$row['RV_LOW_VALUE']] = 0;
    $default_conservation_mode[$row['RV_LOW_VALUE']] = $row['RV_HIGH_VALUE'];
}

// avandest is an array containing internal counter to sort the register samples accordingly 
$registered_samples_new = array();
$ognandest = array();
foreach ($registered_samples as $ognproc => $samples) {
    foreach ($samples as $seqno => $properties) {
        if (!array_key_exists($ognproc, $ognandest)) {
            $ognandest[$ognproc] = $avandest;
        }

        $analyzedest = $properties['ANALYZE_DEST'];
        if (array_key_exists($ognproc . "/" . $ognandest[$ognproc][$analyzedest], $registered_samples_new) &&
            in_array($ognproc . "/" . $analyzedest, $registered_samples_new[$ognproc . "/" . $ognandest[$ognproc][$analyzedest]])
        ) {
            $ognandest[$ognproc][$analyzedest] += 1;
        }

        $registered_samples_new[$ognproc . "/" . $ognandest[$ognproc][$analyzedest]][$analyzedest] = $properties;

        $ognandest[$ognproc][$analyzedest] += 1;
    }
}

// load organs 
//$file_load = !file_exists('loadorganslesions.php') ? 'functions/loadorganslesions.php' : 'loadorganslesions.php';
$file_load = WebFunctions . '/loadorganslesions.php';

//----------------------------------------------------------------------------------
// get Specimen ID 
$sql = "select scn_seqno from spec2events where ese_seqno = $necropsy_seqno";
$res = $db->query($sql);
if ($res->isError()) {
    $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
}
$row = $res->fetch();
$specimenlink = $row == false ? 'init' : $row['SCN_SEQNO'];

$var = $specimenlink; // variable declared in the include file
include(WebFunctions . 'autopsy_specimen_link.php');

?>
<form class='well samples_form <?php echo $this->flowname . '_form'; ?> default_form' action='#'>
    <fieldset id="diagnosis_fs">
        <legend>Samples</legend>
        <table id="samples" class='tab_output samples' width="100%" border="1">
            <thead>
            <tr class='conservation_mode'>
                <th>Conservation Mode</th>
                <?php foreach ($analyze_dest as $key => $vall): ?>
                    <th>
                        <select class='conservation_mode <?php echo $key; ?>'>
                            <?php foreach ($conservation_mode_list as $cons_key => $cons_val) {
                                $selected = $default_conservation_mode[$key] == $cons_key ? "selected='selected'" : '';

                                echo "<option $selected value='$cons_key'>$cons_key</option>";
                            }
                            ?>
                        </select>
                    </th>
                <?php endforeach; ?>
                <th rowspan="2"></th>
            </tr>
            <tr class='analyze_dest'>
                <th>Analysis</th>
                <?php foreach ($analyze_dest as $key => $vall): ?>
                    <th><?php echo $key; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="<?php echo count($analyze_dest) + 2; ?>">
                    <button type="button" class="addsample">Add Sample</button>
                    <button type="button" class="toggledefault hide">Hide Defaults</button>
                    <button type="button" class="toggledefault show" style="display:none;">Show Defaults</button>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php // Hidden row, to be used at the client side?>
            <tr style="display:none;" class='initbodyrow'>
                <td class='organ_select'><?php $lesion_var = 'ROOT';
                    include($file_load);
                    unset($lesion_var); ?></td>
                <?php
                for ($i = 0; $i < count($analyze_dest); $i++):
                    $r = rand(0, 10000);

                    echo "<td class='sample_select'>
                    <div class='maintd'>
                        <span style='visibility:hidden;' class='RegConsMode'></span>
                        <span class='UpdConsMode' style='visibility:hidden;'></span>
                        <span class='UpdOrgan' style='visibility:hidden;'></span>
                        <input type='checkbox' id='availability-" . $r . "' class='availability'/><label for='availability-" . $r . "'>Available</label>";
                    echo $conservation_mode_body;
                    echo $sample_type;
                    echo "</div>
                    </td>";
                endfor
                ?>
                <td class="sample_delete">
                    <input type="checkbox" class="tobedeleted"/><label>Delete</label>
                </td>
            </tr>
            <?php // DISPLAY ALL SAMPLE LESIONS
            $tdclass = 'RegSample';
            $defaults = array();
            foreach ($registered_samples_new as $ognprocnum => $ansamples) :
                list($lesion_var, $processus_var, $num) = explode('/', $ognprocnum);
                $defaults[] = $lesion_var . "/" . $processus_var;
                ?>
                <tr class="reg_sample">
                    <td class='organ_select'>
                        <?php
                        include($file_load);
                        ?>
                    </td>
                    <?php
                    $lesionsamplejson = '';
                    foreach ($analyze_dest as $an_dest => $value):

                        if (array_key_exists($an_dest, $ansamples)) {
                            $registered_sample = $ansamples[$an_dest];

                            $lesionsample = array('lesion' => array($registered_sample['OGN_CODE'], $registered_sample['PROCESSUS']),
                                'CONSERVATION_MODE' => $registered_sample['CONSERVATION_MODE'],
                                'ANALYZE_DEST' => $registered_sample['ANALYZE_DEST'],
                                'SPE_TYPE' => $registered_sample['SPE_TYPE'],
                                'SEQNO' => $registered_sample['SEQNO'],
                                'AVAILABILTY' => $registered_sample['AVAILABILITY'] === 'yes' ? true : false
                            );
                            $lesionsamplejson = json_encode($lesionsample, JSON_PRETTY_PRINT); //TODO CHECK JSON_PRETTY_PRINT
                        } else {
                            $lesionsamplejson = '';
                        }

                        $tdclass = strlen($lesionsamplejson) == 0 ? "RegSampleDefault" : 'RegSample';

                        ?>
                        <td class='sample_select <?php echo $tdclass;?>'>
                            <div>
                                <?php
                                $organcodelesion = "";
                                $consmodeorgan = "";
                                $r = rand(10000, 20000);
                                if (strlen($lesionsamplejson) != 0) {
                                    echo "<span  class='RegConsMode' style='visibility:hidden;>" . $registered_sample['CONSERVATION_MODE'] . "</span>";
                                    $organcodelesion = $registered_sample['OGN_CODE'] . "/NA";
                                    $consmodeorgan = $registered_sample['CONSERVATION_MODE'];
                                } else {
                                    echo "<span class='RegConsMode' style='visibility:hidden;'></span>";
                                }
                                ?>
                                <span class='UpdConsMode' style='visibility:hidden;'><?php echo $consmodeorgan;?></span>
                                <span class='UpdOrgan' style='visibility:hidden;'><?php echo $organcodelesion; ?></span>
                                <?php
                                if (strlen($lesionsamplejson) != 0) {
                                    if ($lesionsample['AVAILABILTY']) {
                                        echo "<input type='checkbox' id='availability-" . $r . "' class='availability' checked/><label for='availability-" . $r . "'>Available</label>";
                                    } else {
                                        echo "<input type='checkbox' id='availability-" . $r . "' class='availability'/><label for='availability-" . $r . "'>Available</label>";
                                    }
                                    //echo "<input type='checkbox' checked = 'checked'/>";
                                    echo "<input class='organlesionsample' style='display:none;' name = 'organlesionsample[]' value = '$lesionsamplejson'/>";
                                    echo "<input class='regorganlesionsample' style='display:none;' value = '$lesionsamplejson'/>";
                                } else {
                                    echo "<input class='addRegSample' type='checkbox'/>";
                                }

                                if (strlen($lesionsamplejson) != 0) {
                                    $presel_sample_type = "<select class='SampleType minwidth'>";
                                    foreach ($sample_type_list as $key => $sample_type_value) {
                                        $selected = $lesionsample['SPE_TYPE'] == $key ? "selected='selected'" : "";
                                        $presel_sample_type .= "<option $selected value ='" . $key . "'>" . $sample_type_value . "</option>";
                                    }
                                    $presel_sample_type .= "</select>";

                                    $presel_conservation_mode = "<select class='CsvModeBody minwidth'>";
                                    foreach ($conservation_mode_list as $key => $conservation_mode_value) {
                                        $selected = $lesionsample['CONSERVATION_MODE'] == $key ? "selected='selected'" : "";
                                        $presel_conservation_mode .= "<option $selected value ='" . $key . "'>" . $conservation_mode_value . "</option>";
                                    }
                                    $presel_conservation_mode .= "</select>";

                                    echo $presel_conservation_mode;
                                    echo $presel_sample_type;
                                }
                                ?>
                            </div>
                        </td>
                    <?php endforeach;?>
                    <td class="sample_delete">
                        <input type="checkbox" class="tobedeleted"/><label>Delete</label>
                    </td>
                </tr>
            <?php
            endforeach; //
            ?>

            <?php


            // DISPLAY ALL ORGANS ROW


            // get organs

            $samplesset = array(); // array of already shown samples in the interface

            $organsroot = array();

            $sql = "select code,name from organs where ogn_code= :ROOT order by name";
            $bind = array(':ROOT' => 'ROOT');
            $res = $db->query($sql, $bind);
            if ($res->isError()) {
                $val->setError('globalerror', $res->errormessage() . '; ' . $sql);
            }
            while ($row = $res->fetch()):

                if (in_array($row['CODE'] . "/NA", $defaults)) {
                    continue;
                }
                ?>
                <tr class='default_sample'>
                    <td class='organ_select'><?php $lesion_var = $row['CODE'];
                        include($file_load);
                        unset($lesion_var);?></td>
                    <?php
                    foreach ($analyze_dest as $key => $value):
                        ?>
                        <td class='RegSampleDefault'>
                            <div>
                                <span class='RegConsMode' style='visibility:hidden;'></span>
                                <span class='UpdConsMode' style='visibility:hidden;'></span>
                                <span class='UpdOrgan' style='visibility:hidden;'></span>
                                <input type='checkbox'/>
                            </div>
                        </td><?php endforeach;?>
                    <td class="sample_delete">
                        <input type="checkbox" class="tobedeleted"/><label>Delete</label>
                    </td>
                </tr>
            <?php endwhile;
            ?>
            </tbody>
        </table>
        <div class='errormessage'><?php echo $val->getError('globalerror'); ?></div>
        <p id='organ_change_dialog' style='display:none;'>You've changed the organ this sample is taken from. Please note that then all other samples taken from this organ will now also be taken from the 'new' organ. If you wish to keep the other samples attached to the 'old' organ, delete and recreate them.<p>
    </fieldset>
    <?php echo $this->getButtons(); ?>
</form>




