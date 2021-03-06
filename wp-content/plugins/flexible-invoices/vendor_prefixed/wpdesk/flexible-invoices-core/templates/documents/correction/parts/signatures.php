<?php

namespace WPDeskFIVendor;

/**
 * File: parts/signatures.php
 */
if ($settings->get('show_signatures') === 'yes') {
    ?>
    <table id="signatures">
        <tr>
            <td>
                <p class="user"></p>
                <p>&nbsp;</p>
                <p>........................................</p>
            </td>

            <td width="15%"></td>

            <td>
                <?php 
    if (!empty($owner->get_signature_user()) && !empty($owner->get_signature_user())) {
        ?>
                    <p class="user">
                        <?php 
        $user = \get_user_by('id', $owner->get_signature_user());
        if (isset($user->data->display_name) && !empty($user->data->display_name)) {
            echo $user->data->display_name;
        } else {
            echo $user->data->user_login;
        }
        ?>
                    </p>
                <?php 
    }
    ?>
                <p>&nbsp;</p>
                <p>........................................</p>
            </td>
        </tr>

        <tr>
            <td>
                <p><?php 
    echo \__('Buyer signature', 'flexible-invoices');
    ?></p>
            </td>

            <td width="15%"></td>

            <td>
                <p><?php 
    echo \__('Seller signature', 'flexible-invoices');
    ?></p>
            </td>
        </tr>
    </table>
<?php 
}
