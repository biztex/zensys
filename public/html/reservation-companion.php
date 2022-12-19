
    <div class="d-flex align-items-center mb-2 flex-wrap justify-content-center justify-content-md-start">
        <span class="requiredRed if-non-member">同行者がいない場合は×で入力枠を閉じて、確認画面へ進んでください。</span>
        <div class="ml-3"><a type="button" class="grayBtn copyAddress helperTextOver">参加者と同行者がの住所が同一の場合<br>こちらをクリック</a></div>
    </div>
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
        <tr class="zipTr companionAddress">
            <th class="topTh">住所<span class="requiredRed">※</span></th>
            <td>
                <div class="">
                    <div class="zipP01">
                        <span>〒</span>
                        <input class="midIpt companion_p-postal-code" type="text" name="companion_postalCode[]" pattern="\d{7}"  placeholder="ハイフンなしで入力" required>
                    </div>
                    <span class="errorMessage"></span>
                </div>
                <select id="companion_contact_address_pref" name="companion_prefecture[]" required class="p-region midIpt">
                    <option value="北海道">北海道</option>
                    <option value="青森県">青森県</option>
                    <option value="岩手県">岩手県</option>
                    <option value="宮城県">宮城県</option>
                    <option value="秋田県">秋田県</option>
                    <option value="山形県">山形県</option>
                    <option value="福島県">福島県</option>
                    <option value="茨城県">茨城県</option>
                    <option value="栃木県">栃木県</option>
                    <option value="群馬県">群馬県</option>
                    <option value="埼玉県">埼玉県</option>
                    <option value="千葉県">千葉県</option>
                    <option value="東京都">東京都</option>
                    <option value="神奈川県">神奈川県</option>
                    <option value="新潟県">新潟県</option>
                    <option value="富山県">富山県</option>
                    <option value="石川県">石川県</option>
                    <option value="福井県">福井県</option>
                    <option value="山梨県">山梨県</option>
                    <option value="長野県">長野県</option>
                    <option value="岐阜県">岐阜県</option>
                    <option value="静岡県">静岡県</option>
                    <option value="愛知県">愛知県</option>
                    <option value="三重県">三重県</option>
                    <option value="滋賀県">滋賀県</option>
                    <option value="京都府">京都府</option>
                    <option value="大阪府">大阪府</option>
                    <option value="兵庫県">兵庫県</option>
                    <option value="奈良県">奈良県</option>
                    <option value="和歌山県">和歌山県</option>
                    <option value="鳥取県">鳥取県</option>
                    <option value="島根県">島根県</option>
                    <option value="岡山県">岡山県</option>
                    <option value="広島県">広島県</option>
                    <option value="山口県">山口県</option>
                    <option value="徳島県">徳島県</option>
                    <option value="香川県">香川県</option>
                    <option value="愛媛県">愛媛県</option>
                    <option value="高知県">高知県</option>
                    <option value="福岡県">福岡県</option>
                    <option value="佐賀県">佐賀県</option>
                    <option value="長崎県">長崎県</option>
                    <option value="熊本県">熊本県</option>
                    <option value="大分県">大分県</option>
                    <option value="宮崎県">宮崎県</option>
                    <option value="鹿児島県">鹿児島県</option>
                    <option value="沖縄県">沖縄県</option>
                </select>

                <div class="zipP02">
                    <p>市区郡町村</p>
                    <input type="text" name="companion_address[]" class="width-half"  required>
                </div>
                <div class="zipP02">
                    <p>番地、アパート、マンション名等</p>
                    <input class="width-half" type="text" name="companion_extended[]"  required>
                </div>
            </td>
        </tr>
        <tr class="child_boarding">
        </tr>
        <tr class="child_drop">
        </tr>
    </table>
    <p class="reserveDelete"></p>
