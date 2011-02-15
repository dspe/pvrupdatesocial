{default attribute_base=ContentObjectAttribute}
{if ne( $attribute_base, 'ContentObjectAttribute' )}
    {def $id_base = concat( 'ezcoa-', $attribute_base, '-', $attribute.contentclassattribute_id, '_', $attribute.contentclass_attribute_identifier )}
{else}
    {def $id_base = concat( 'ezcoa-', $attribute.contentclassattribute_id, '_', $attribute.contentclass_attribute_identifier )}
{/if}

<div class="pvrUpdateStatutList">
	<ul>
		{if $attribute.content.is_valid}
		<li>
			<label>Twitter</label>
			<div class="block">
				{$attribute.content.year}-{$attribute.content.month}-{$attribute.content.day}{/if} {$attribute.content.hour}:{$attribute.content.minute}
			</div>
			
			<div class="break"></div>
			
			</div>
		</li>
		{/if}
	</ul>
</div>