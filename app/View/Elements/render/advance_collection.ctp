<table  class="table table-striped table-bordered table_scrol">
    <thead>
        <tr>
            <th>S/A:</th>
            <th>Collection/Type</th>
            <th>Invoice Ref. No</th>
            <th>Billing Percent</th>
            <th>Invoice Amount</th>
            <th>Amount Received</th>
            <th>AIT</th>
            <th>VAT</th>
            <th>L.D</th>
            <th>Other Deduction</th>
            <th>Currency</th>
            <th>Planned Invoice Submission Date</th>
            <th>Actual Invoice Submission Date</th>                     
            <th>Planned Payment Certificate/Cheque Collection Date</th>           
            <th>Planned Payment Credited to Bank Date</th>            
            <th>Added By</th>
            <th>Added Date</th>             
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sl=1;
        foreach ($collections as $collection): ?>
            <tr> 
                <td><?php echo $sl++; ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['collection_type']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['invoice_ref_no']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['billing_percent']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['invoice_amount']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['amount_received']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['ait']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['vat']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['ld']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['other_deduction']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['currency']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['planned_submission_date'] != "0000-00-00 00:00:00") ? $collection['Collection']['planned_submission_date'] : 'N/A'; ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['actual_submission_date'] != "0000-00-00 00:00:00") ? $collection['Collection']['actual_submission_date'] : 'N/A'; ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['planned_payment_certificate_or_cheque_collection_date'] != "0000-00-00") ? $collection['Collection']['planned_payment_certificate_or_cheque_collection_date'] : 'N/A'; ?>&nbsp;</td>
                
                <td><?php echo h($collection['Collection']['planned_payment_collection_date'] != "0000-00-00") ? $collection['Collection']['planned_payment_collection_date'] : 'N/A'; ?>&nbsp;</td>
               
                <td><?php echo h($collection['Collection']['added_by']); ?>&nbsp;</td>
                <td><?php echo h($collection['Collection']['added_date']); ?>&nbsp;</td>                 
                <td><?php echo h($collection['Collection']['remarks']); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>            
    </tbody>
    <tfoot>
     
            <tr>
                <td colspan="26"></td>
            </tr>
         
    </tfoot>
</table>
