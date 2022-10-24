    <span class="requiredRed if-non-member">同行者がいない場合は×で入力枠を閉じて、確認画面へ進んでください。</span>
    <table class="reserveTable">
        <tr class="nameTr">
            <th>同行者氏名(漢字)<span class="requiredRed">※</span></th>
            <td class="sp-pt-20">
                <div class="halfP">
                    <span>姓</span>
                    <div>
                        <input class="midIpt" type="text" name="add_lastname[]" required>
                        <span class="errorMessage"></span>
                    </div>
                </div>
                <div class="halfP">
                    <span>名</span>
                    <div>
                        <input class="midIpt" type="text" name="add_firstname[]" required>
                        <span class="errorMessage"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="nameTr">
            <th>同行者氏名(カナ)<span class="requiredRed">※</span></th>
            <td>
                <div class="halfP">
                    <span>セイ</span>
                    <div>
                        <input class="midIpt" type="text" name="join_kana1[]" required>
                        <span class="errorMessage"></span>
                    </div>
                </div>
                <div class="halfP">
                    <span>メイ</span>
                    <div>
                        <input class="midIpt" type="text" name="join_kana2[]" required>
                        <span class="errorMessage"></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="ageTr">
            <th>年齢<span class="requiredRed">※</span></th>
            <td>
                <div>
                    <div class="ageP">
                        <input class="midIpt" type="text" name="join_age[]" required>
                        <span>才</span>
                    </div>
                    <span class="errorMessage"></span>
                </div>
            </td>
        </tr>
        <tr>
        <th>生年月日<span class="requiredRed">※</span></th>
            <td>
                <div>
                    <div class="dateP">
                        <select name="birth_year_representative[]" required><?php for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) { if($i == (date('Y') - 60)) {?><option value="" selected disabled>選択してください</option><?php }?><option value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php }?></select> 年　<select name="birth_month_representative[]" required><option value="">選択してください</option><?php for ($i = 1 ; $i <= 12 ; $i++){?> <option value="<?php echo $i;?>"><?php echo $i; ?></option> <?php }?></select> 月　<select name="birth_day_representative[]" required><option value="">選択してください</option><?php for ($i = 1 ; $i <= 31 ; $i++) {?><option value="<?php echo $i; ?>"  ><?php echo $i ;?></option> <?php }?></select> 日
                    </div>
                    <span class="errorMessage"></span>
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