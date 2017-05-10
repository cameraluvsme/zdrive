<section>
    <form action="search_confirm.php" method="post">
    <table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
      <thead>
        <tr>
          <td colspan="2" style="text-align: center;">SELECT YOUR PREFERENCE</td>
        </tr>
      </thead>
      <tfoot>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" value = "SEARCH" name = "search_btn">
            </td>
          </tr>
      </tfoot>
      <tobody>
        <tr>
          <td align="right" nowrap>
            <label>Preference</label>
          </td>
          <td valign="top">
            <label><input type="radio" name="pref" value="NEW">NEW</label><br>
            <label><input type="radio" name="pref" value="USED">USED</label>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap>
            <label>Maker</label>
          </td>
          <td valign="top">
            <label><input type="checkbox" name="maker[]" value="Nissan" <?php if($maker == maker){echo "checked";} ?>>Nissan</label><br>
            <label><input type="checkbox" name="maker[]" value="Toyota" <?php if($maker == maker){echo "checked";} ?>>Toyota</label><br>
            <label><input type="checkbox" name="maker[]" value="Honda" <?php if($maker == maker){echo "checked";} ?>>Honda</label><br>
            <label><input type="checkbox" name="maker[]" value="Mazda" <?php if($maker == maker){echo "checked";} ?>>Mazda</label>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap>
            <label>Type</label>
          </td>
          <td valign="top">
            <select name="type[]" size = "4" multiple>
              <option value="SUV" <?php if($type == SUV){echo "selected";} ?>>SUV</option>
              <option value="Van" <?php if($type == Van){echo "selected";} ?>>Van</option>
              <option value="Compact" <?php if($type == Compact){echo "selected";} ?>>Compact</option>
              <option value="Wagon" <?php if($type == Wagon){echo "selected";} ?>>Wagon</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap>
            <label>Year</label>
          </td>
          <td valign="top">
            <select name="year" size = "4">
              <option value="2012" <?php if($year == 2012){echo "selected";} ?>>2012</option>
              <option value="2010" <?php if($year == 2010){echo "selected";} ?>>2010</option>
              <option value="2008" <?php if($year == 2008){echo "selected";} ?>>2008</option>
              <option value="2005" <?php if($year == 2005){echo "selected";} ?>>2005</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap>
            <label>Price</label>
          </td>
          <td valign="top">
            <label><input type="number" name="price"
    value="<?php echo $price; ?>" step = "25" min= "25" max = "900">Man Yen</label>
          </td>
        </tr>
      </tobody>
    </table>
  </form>
</section>