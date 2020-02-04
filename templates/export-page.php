<div style="clear:both;">
  <form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
    <input type="hidden" name="action" value="magellan_export" />
    <div class="postbox-container">
      <div class="metabox-holder">
        <div class="postbox">
          <h3 class="hndle"><span>Magellan Export Closed Data</span></h3>
          <div class="inside">
            <p>
              <label for="magellan_export_format"><strong>Export Format:</strong></label>
            </p>
            <p>
              <select id="magellan_export_format" name="magellan_export_format">
                <option value="csv-unique">CSV: Dates in distinct columns</option>
                <option value="csv-single">CSV: Dates in single column</option>
              </select>
            </p>

            <?php  submit_button("Export"); ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>