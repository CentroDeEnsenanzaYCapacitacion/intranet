<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_id',
        'user_id',
        'student_id',
        'concept',
        'amount',
        'receipt_type_id',
        'payment_type_id',
        'attr_id',
        'receipt_amount',
        'card_payment',
        'voucher',
        'bill',
        'folio',
        'status',
        'report_id',
        'receipt_attribute_id',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiptType()
    {
        return $this->belongsTo(ReceiptType::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
