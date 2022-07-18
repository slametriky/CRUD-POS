<?php

namespace App\Actions;

use Exception;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\DB;

class SaveSalesOrder
{
    private SalesOrder $salesOrder;
    private array $attributes;

    public function __construct(SalesOrder $salesOrder, array $attributes = [])
    {
        $this->salesOrder = $salesOrder;
        $this->attributes = $attributes;
    }

    public function handle(): void
    {
        DB::beginTransaction();

        try {
            
            $this->salesOrder->customer()->associate($this->attributes['customer_id']);
            $this->salesOrder->fill($this->attributes)->save();

            collect($this->attributes['items'])->each(function ($item) {

                $salesOrderItem = new SalesOrderItem();
                $salesOrderItem->salesOrder()->associate($this->salesOrder);
                $salesOrderItem->product()->associate($item['id']);
                
                if($item['quantity'] != 0){
                    $salesOrderItem->fill($item)->save();
                }
            });
            
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            throw new Exception($exception->getMessage());
        }
    }
}
