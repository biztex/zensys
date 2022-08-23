    <table class="reserveTable">
        <tr class="nameTr">
            <th>同行者氏名(漢字)<span class="requiredRed">※</span></th>
            <td class="sp-pt-20">
                <div class="halfP">
                    <span>姓</span>
                    <input class="midIpt" type="text" name="add_lastname[]" required>
                </div>
                <div class="halfP">
                    <span>名</span>
                    <input class="midIpt" type="text" name="add_firstname[]" required>
                </div>
            </td>
        </tr>
        <tr class="nameTr">
            <th>同行者氏名(カナ)<span class="requiredRed">※</span></th>
            <td>
                <div class="halfP">
                    <span>セイ</span>
                    <input class="midIpt" type="text" name="join_kana1[]" required>
                </div>
                <div class="halfP">
                    <span>メイ</span>
                    <input class="midIpt" type="text" name="join_kana2[]" required>
                </div>
            </td>
        </tr>
        <tr class="ageTr">
            <th>年齢<span class="requiredRed">※</span></th>
            <td>
                <div class="ageP">
                    <input class="midIpt" type="text" name="join_age[]" required>
                    <span>才</span>
                </div>
            </td>
        </tr>
        <tr>
        <th>生年月日<span class="requiredRed">※</span></th>
            <td>
                <div class="dateP">
                <select name="birth_year_representative[]"><?php for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) {?><option value="<?php echo $i; ?>" <?php if ($i == (date('Y'))) { echo 'selected';} ?>><?php echo $i; ?></option> <?php }?></select> 年　<select name="birth_month_representative[]"><?php for ($i = 1 ; $i <= 12 ; $i++){?> <option value="<?php echo $i;?>" <?php if ($i == 1) { echo 'selected';} ?>><?php echo $i; ?></option> <?php }?></select> 月　<select name="birth_day_representative[]"><?php for ($i = 1 ; $i <= 31 ; $i++) {?><option value="<?php echo $i; ?>"  <?php if ($i == 1){ echo 'selected';} ?> ><?php echo $i ;?></option> <?php }?></select> 日
                </div>
            </td>
        </tr>
        <tr>
            <th>性別<span class="requiredRed">※</span></th>
            <td>
                <div class="radioBox">
                    <select name="join_sex[]">
                        <option value="0">
                        男性
                    <option value="1">
                        女性
                </div>
            </td>
        </tr>
        <tr class="child_boarding">
        </tr>
        <tr class="child_drop">
        </tr>
    </table>
    <p class="reserveDelete"></p>