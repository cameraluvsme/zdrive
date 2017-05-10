<section class="contact">
                  <form action="" method="post"  id="contact" novalidate>
                  <input type="hidden" name="aaa" value="<?php echo $aaa; ?>">
                  <input type="hidden" name="confirm" value="<?php echo $confirm; ?>">
                  <input type="hidden" name="sent" value="<?php echo $sent; ?>">
                  <input type="hidden" name="fstvisit" value="<?php echo $fstvisit; ?>">
                  <input type="hidden" name="token" value="<?php echo getToken(); ?>">
                <table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center;">
                                下記ご入力ください<br>
                                <span style="margin-left: 3%">*入力必須項目</span>
                            </th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <td colspan="2" align="center">
                            <input type="submit" value="Confirm" id="send_btn" name = "confirmbtn">
                            <?php if (isset($errorName)): ?>
                                <div class="text-warning"><?php echo h($errorName); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorKana)): ?>
                                <div class="text-warning"><?php echo h($errorKana); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorEmail)): ?>
                                <div class="text-warning"><?php echo h($errorEmail); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorPhone)): ?>
                                <div class="text-warning"><?php echo h($errorPhone); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorInquiry)): ?>
                                <div class="text-warning"><?php echo h($errorInquiry); ?></div>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </tfoot>
                      <tbody>
                        <tr>
                          <td align="right" nowrap>
                            <label for = "user">お名前<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="text" name="name" maxlength="20" required id="user" size="18" value="<?php echo h($name); ?>">
                          </td>
                        </tr>
                        <tr>
                          <td align="right" nowrap>
                            <label for = "pronouce">カナ<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="text" name="kana"  maxlength="20" size="18" placeholder="全角カタカナ入力" id = "pronouce" value="<?php echo h($kana); ?>">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "eaddress">メール<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="email" name="email"  maxlength="40" required size="18" id = "eaddress" value="<?php echo h($email); ?>" >
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "phonenum">TEL<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="tel" name="phone" maxlength="20" size="18" id = "phonenum" value="<?php echo h($phone); ?>">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "inquiry_string">内容<span>*</span></label>
                          </td>
                          <td valign="top">
                            <textarea name="inquiry" cols="23" rows="6" maxlength="200" required id = "inquiry_string"><?php echo h($inquiry); ?></textarea>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                  <div id="shopping_done">
                    <h4>
                    <!--<span></span>様<br>-->お問い合わせありがとうございました
                    <i class="fa fa-smile-o" aria-hidden="true"></i><br><br>
                    <!--<a href="">戻る</a>-->
                    確認メールを送信いたしましたので、<br>
                    ご登録のメールをご覧くださいませ
                    <i class="fa fa-inbox" aria-hidden="true"></i>
                    </h4>
                    <form action="" method = "post">
                      <input type="submit" value="FORM" id="send_btn" class = "form_back" name = "formback">
          </form>
                  </div>
                </section>