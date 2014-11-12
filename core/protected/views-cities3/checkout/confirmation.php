<?php
	$modelId = 'Confirmation';
?>

<div class="section-content" id="confirm">
	<nav class="steps">
		<ol>
			<li class="completed"><span class="webstore-label"></span><?php echo Yii::t('checkout', "Shipping")?></li>
			<li class="completed"><span class="webstore-label"></span><?php echo Yii::t('checkout', "Payment")?></li>
			<li class="current"><span class="webstore-label"></span><?php echo Yii::t('checkout', "Confirmation")?></li>
		</ol>
	</nav>
	<h1><?php echo Yii::t('checkout', "Confirm Your Order")?></h1>
	<?php
		$form = $this->beginWidget(
			'CActiveForm',
			array('htmlOptions' => array('novalidate' => '1'))
		);
	?>
	<div class="error-holder"><?= $error ?></div>
	<div class="final-confirmation">
		<?php
			echo CHtml::htmlButton(
				Yii::t('checkout', 'Place Order'),
				array(
					'class' => 'button',
					'id' => 'place-order',
					'type' => 'submit',
					'name' => 'Confirmation'
				)
			);?>
		<label class="checkbox">
			<?php
				echo $form->checkBox(
					$model,
					'receiveNewsletter',
					$htmlOptions = array('checked' => Yii::app()->params['DISABLE_ALLOW_NEWSLETTER'] ? '' : 'checked')
				);

				echo Yii::t('checkout', "I'd like to receive special offers and product information by email");
			?>
		</label>
		<div class="comments">
			<a href="#" class="hasborder" onclick="$('.comments a').hide(); $('.comments textarea').fadeToggle().focus(); return false;">
				<?php
					echo Yii::t('checkout', 'Add a comment or special request');
				?>
			</a>
			<?php
				echo $form->textArea(
					$model,
					'orderNotes',
					array('placeholder' => Yii::t('checkout', 'Enter your comment or special request'))
				);
			?>
		</div>
		<p class="terms">
			<?php
				echo Yii::t('checkout', 'By clicking "Place Order" you agree to our ');
				echo
					CHtml::link(
						Yii::t('checkout', 'Terms and Conditions'),
						array("/terms-and-conditions"),
						array('target' => '_blank', 'class' => 'hasborder' )
					);
				echo
					$form->checkBox(
						$model,
						'acceptTerms',
						array('checked' => 'checked', 'style' => 'display: none')
					);
			?>
		</p>
	</div>
	<?php
		echo $form->hiddenField($model, 'billingSameAsShipping');
		echo $form->hiddenField($model, 'intBillingAddress');
		echo $form->hiddenField($model, 'billingAddress1');
		echo $form->hiddenField($model, 'billingAddress2');
		echo $form->hiddenField($model, 'billingCity');
		echo $form->hiddenField($model, 'billingState');
		echo $form->hiddenField($model, 'billingPostal');
		echo $form->hiddenField($model, 'billingCountry');
		echo $form->hiddenField($model, 'paymentProvider');
		echo $form->hiddenField($model, 'cardNameOnCard');
		echo $form->hiddenField($model, 'cardNumber');
		echo $form->hiddenField($model, 'cardCVV');
		echo $form->hiddenField($model, 'cardExpiryMonth');
		echo $form->hiddenField($model, 'cardExpiryYear');
		echo $form->hiddenField($model, 'cardType');

		$this->endWidget();
	?>

	<?php $this->renderPartial('_orderdetails', array('cart' => $cart, 'isReceipt' => false)); ?>

	<article class="cart">
		<?php
			$this->widget(
				'zii.widgets.grid.CGridView',
				array(
					'htmlOptions' => array(
						'class' => 'lines lines-container'
					),
					'id' => 'user-grid',
					'itemsCssClass' => 'lines',
					'dataProvider' => Yii::app()->shoppingcart->dataProvider,
					'summaryText' => '',
					'columns' => array(
						array(
							'type' => 'raw',
							'value' => '"<strong>".$data->qty."</strong>".CHtml::numberField("CartItem_qty[$data->id]",$data->qty,array(
													"data-pk"=>$data->id,
													"size" => "3",
													"onchange" =>"wsEditCartModal.updateCart(this)",
												))',
							'htmlOptions' => array(
								'class' => 'quantity',
								'size' => '2'
							),
						),
						array(
							'type' => 'raw',
							'value' => '
								CHtml::image($data->product->SliderImage) .
								CHtml::tag("td",array("class" => "description"),
								CHtml::link("<strong>".$data -> product->title." "."</strong>" .
								Yii::app()->getController()->renderPartial(\'_formattedCartItemSellPriceWithDiscount\', array(\'cartItem\' => $data), true), $data -> product->Link, array())
								)',
							'htmlOptions' => array(
								'class' => 'image'
							),
						),
						array(
							'type' => 'raw',
							'value' => 'CHtml::link("Edit","#",array(
								//"data-pk"=>$data->id,"class"=>"edit", "onclick"=>"$(this).closest(\'tr\').addClass(\'active\'); $(this).closest(\'input\').focus();  return false;")
								"data-pk"=>$data->id,"class"=>"edit", "onclick"=>"$(this).closest(\'tr\').addClass(\'active\'); $(this).closest(\'input\').focus(); return false;")
								).CHtml::link("&times;","#",array("data-pk"=>$data->id,"class"=>"remove", "onclick"=>"wsEditCartModal.removeItem(this); return false;"))',
							'htmlOptions' => array(
								'class' => 'controls'
							),

						),
						array(
							'type' => 'raw',
							'value' => '$data->sellTotalFormatted',
							'htmlOptions' => array(
								'class' => 'subtotal'
							),
						),
					),
					'rowHtmlOptionsExpression' => 'array("id" => "cart_row_".$data->id)',
				)
			);
		?>
		<div class="lines-footer">
			<form class="promo">
				<?php
					echo
						CHtml::tag(
							'div',
							array(
								'id' => CHtml::activeId($modelId, 'promoCode') . '_em_',
								'class' => 'form-error',
								'style' => 'display: none'
							),
							'<p>&nbsp;</p>'
						);
				?>
				<div style="position:relative;">
					<?php
						$this->renderPartial(
							'ext.wscartmodal.views._promocodeinput',
							array('modelId' => $modelId, 'updateCartTotals' => true, 'reloadPageOnSuccess' => false)
						);
					?>
				</div>
				<div class="form-error" style="display: none">
					<p>something bad happened...</p>
				</div>
				<p class="description">
					<?php echo Yii::t('cart', 'Specials, promotional offers and discounts') ?>
				</p>
			</form>
			<div class="totals">
				<table class="totals">
					<tbody>
						<tr id="PromoCodeLine" class="<?= Yii::app()->shoppingcart->promoCode ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
							<th colspan='3'>
								<?= Yii::t('cart', 'Promo & Discounts'); ?>
								<td id="PromoCodeStr" class="promo-code-str">
									<?= Yii::app()->shoppingcart->totalDiscountFormatted; ?>
								</td>
							</th>
						</tr>
						<tr>
							<th colspan='3'><?php echo Yii::t('cart', 'Shipping'); ?>
								<small><?= Yii::app()->shoppingcart->shipping->shipping_data; ?></small>
							</th>
							<td class="shipping-estimate">
								<?= _xls_currency(Yii::app()->shoppingcart->shipping_sell); ?>
							</td>
						</tr>

						<?php
							$this->renderPartial(
								'_checkout-taxes',
								array(
									'cart' => Yii::app()->shoppingcart,
									'selectedCartScenario' => null,
									'confirmation' => true
								)
							);
						?>

						<tr class="total">
							<th colspan='3'><?php echo Yii::t('cart', 'Total'); ?></th>
							<td id="totalCart" class="wsshippingestimator-total-estimate total-estimate">
								<?= _xls_currency(Yii::app()->shoppingcart->total); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</article>
</div>
<?php $this->publishJS('confirmationShippingEstimator'); ?>
<?php
Yii::app()->clientScript->registerScript(
	'instantiate checkout',
	sprintf(
		'$(document).ready(function () {
			checkout = new Checkout(%s);
		});',
		Checkout::getCheckoutJSOptions()
	),
	CClientScript::POS_HEAD
);
?>
<script>
	// TODO: Move these into a strings object or pass in as an option.
	strCalculateButton = <?= CJSON::encode(Yii::t('shipping', 'Calculate')) ?>;
	calculatingLabel = <?= CJSON::encode(Yii::t('cart', 'Calculating...')) ?>;

	$(document).ready(function () {
		confirmationShippingEstimator = new ConfirmationShippingEstimator(<?= CJSON::encode($shippingEstimatorOptions) ?>);

		<?php
			// TODO: It isn't ideal to bind the promoCodeInput - ideally
			// confirmation.php wouldn't need to know about
			// promoCodeInput. But we aren't presently managing the
			// application in such a way that there's an easy alternative.
		?>

		if (typeof promoCodeInput !== 'undefined') {
			promoCodeInput.wsShippingEstimator = confirmationShippingEstimator;
		}
	});
</script>
<?php
	Yii::app()->clientScript->registerScript(
		'instantiate wsEditCartModal',
		sprintf(
			'$(document).ready(function () {
				wsEditCartModal = new WsEditCartModal(%s);
				wsEditCartModal.checkout = checkout;
				wsEditCartModal.wsShippingEstimator = confirmationShippingEstimator;
			});',
			CJSON::encode(
				array(
					'updateCartUrl' => Yii::app()->createUrl('/cart/updatecartitem'),
					'csrfToken' => Yii::app()->request->csrfToken,
					'cartId' => CHtml::activeId($modelId, 'promoCode'),
					'invalidQtyMessage' => Yii::t(
						'checkout',
						'<strong>Only {qty} are available at this time.</strong><br> If you’d like ' .
						'to order more please return at a later time or contact us.'
					)
				)
			)
		),
		CClientScript::POS_END
	);