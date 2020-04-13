@section('styles')
<style>
{
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: calc(100vh - 200px);
    overflow-y: scroll;
}
  .lnk-button {
    background: none;
    border: none;
    color: black;
    text-decoration: underline;
  }
</style>
@stop
<!-- Button trigger modal -->
<button type="button" class="lnk-button" data-toggle="modal" data-target="#termsAndConditionsModal">
  Terms and Conditions
</button>

<!-- Modal -->
<div class="modal fade" id="termsAndConditionsModal" role="dialog" aria-labelledby="termsAndConditionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 65%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsAndConditionsModalLabel" style="display: inline-block;">Terms and conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>1. Quotations/Orders/Contract Quotations </strong> are only valid in writing and during the period that they state. If unstated, the period is 3 days. Please check the Order Confirmation and notify Bizzmo of any mistake in writing immediately or the details stated in the Order confirmation will apply to this Agreement. All product and pricing information is based on latest information available. Subject to change without notice or obligation. The customer cannot cancel or change a Purchase Orders or Order Confirmation sent to Bizzmo. The customer/s are obliged to accept the deliveries within (2) calendar days from the time the products are made available at the place of delivery stated in the order confirmation. If the customer refuses or delay delivery without Bizzmo's agreement, the customer must pay Bizzmo's expenses or loss resulting from that refusal, including storage costs, until you accept delivery. Nothing in this agreement affects Bizzmo's right to cancel or reject any order at any time. </p>
		<p><strong>2. Pricing </strong>Products and service offering prices, tax, shipment, insurance and installation are as shown on the invoice. Changes to exchange rates, duties, insurance, freight, market condition, and purchase costs (incl. for components and Services) may cause Bizzmo to adjust prices accordingly.</p>
		<p><strong>3. Payment Terms & Payment Obligation </strong>Payment will be made as agreed in writing by Bizzmo or in absence of such agreement, within 30 days of the invoice date without further notice from Bizzmo. Payment timing is of the essence. Bizzmo may suspend deliveries or Service until full payment for that order. If payment is late, Bizzmo may charge a late payment charge of 2% monthly accruing on a day-to-day basis for each day of late payment, subject to maximum limitation by law, (unless we otherwise elect) on the overdue amount, and the costs of recovery shall be payable by the customer. The customer agrees that all invoices and any other amounts due under this agreement are payable solely to us in the currency of payment stated above, in full without any set off, counter-claims, abatement, or reduction. We may at our sole discretion apply payments made to us (whether by you or otherwise) to pay late payment charges, invoices overdue interest, or any outstanding amounts. The customer must pay all sums due to us under this agreement including invoices and other charges to us in full, without abatement, discount, reduction, set off, dispute or counterclaim. The customer will not assert against Bizzmo any claims the customer may have against any third party including the manufacturer or original supplier or shipper of goods. We have no obligation to perform any obligation by any third party. Bizzmo may set off against any amounts owed by Bizzmo to the customer any amount dues from the customer to Bizzmo (including those prospectively or contingently due where i n our reasonable opinion they are likely to become payable).  </p>
		<p><strong>4. Delivery/Title/Risk </strong>The Delivery period in the Order Confirmation is approximate. Partial deliveries may be made. The place of delivery is stated in the Order Confirmation. Title to Product passes on full payment and until then the customer must insure the goods and the customer must not modify or pledge them. The customer may use the goods, without modification, in the ordinary course of business. Bizzmo reserves the rights to enter the storage premises to repossess the goods. If the customer sells them before title passes, the customer will become Bizzmo's agent and the proceeds of such sale shall be held on Bizzmo's behalf separately from the customer's general funds. Bizzmo may sue for the Price before title passes. If the customer refuses delivery without Bizzmo's agreement, the customer must pay Bizzmo's expenses or loss resulting from that refusal, including storage costs, until the customer accepts delivery. All risk of the loss of the goods passes to the customer upon delivery. Any missing or damaged packaging should be noted on the waybill prior to signing it by the customer or its nominated shipping agent.  </p>
		<p><strong>5. Trade and Import Authorizations </strong>The customer hereby warrant and represent that the customer have and shall continue to have the due authorizations and licenses necessary and required to purchase the Products and any other products purchased from the Supplier and to import the same into the relevant country. Any failure by the customer to clear the Products from the relevant customs or other authorities in the relevant county whether due to the failure to obtain or maintain the requisite authorizations or licenses or for any other reason whatsoever, will not invalidate this agreement and the customer shall remain bound by the terms of this agreement including liability to make payments to Bizzmo under the terms of this Agreement.  </p>
		<p><strong>6. Acceptance </strong>When the customer or its nominated shipping agent receive the products, the customer or its nominated shipping agent must inspect the products for any defects or non-conformity, and if any, the customer must notify Bizzmo immediately and mention any discrepancies on the proof of delivery. After this, the customer will have accepted Product. If Bizzmo agrees to the return of Product at its choosing, it must be in its original condition with packaging, a return note and proof of purchase;  </p>
		<p><strong>7. Export Control </strong>The customer acknowledge that Product may include technology and Software which is subject to US and EU export control laws and laws of the country where it is delivered or used: the customer must abide by all these laws. Product may not be sold, leased or transferred to restricted / embargoed end users or countries or for a user involved in weapons of mass destruction or genocide without the prior consent of the US or competent EU government. The customer understands and 
acknowledges that US and EU restrictions vary regularly and depending on Product, therefore you must refer to the current US and EU regulations.  </p>
		<p><strong>8. Foreign Corrupt Practices Act (FCPA) </strong>Each Party shall comply with all applicable laws and regulations enacted to combat bribery and corruption, including the United States Foreign Corrupt Practices Act ("FCPA"), the local Bribery Acts, the principles of the OECD Convention on Combating Bribery of Foreign Public Officials (the "OECD Convention") and any corresponding laws of all countries where business or services will be conducted or performed pursuant to this Agreement. Any amounts paid by Supplier to Introducer pursuant to the terms of this Agreement will be for the services actually rendered, or products sold, in accordance with the terms of this Agreement. Introducer shall not directly or indirectly through a third party pay, offer, promise to pay, or give anything of value (including any amounts paid or credited by Supplier to Introducer) to any person including an employee or official of a government, government controlled enterprise or company, or vendor or customer or political party, with the reasonable knowledge that it will be used for the purpose of obtaining any improper benefit or to improperly influence any act or decision by such person or party for the purpose of obtaining, retaining, or directing business.  </p>
		<p><strong>9. Confidentiality </strong>Each party must treat all information received from the other marked "confidential" or reasonably obvious to be confidential as it would treat its own confidential information. </p>
		
      </div>

      <div class="modal-footer">
        <a data-dismiss="modal" aria-label="Close" class="btn btn-info fixedw_button bm-btn green" id="lnksubmit" type="button" title="Save">
          OK
        </a>
      </div>
    </div>
  </div>
</div>