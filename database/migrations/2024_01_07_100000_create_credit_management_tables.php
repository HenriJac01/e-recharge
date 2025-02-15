<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des opérateurs
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ussd_code')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Table des clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cin', 12)->unique();
            $table->string('phone_number', 10)->unique();
            $table->string('secret_code', 4);
            $table->string('adress');
            $table->decimal('balance', 10, 2)->default(0);
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->timestamps();
        });

        // Table des stocks
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('minimum_threshold', 10, 2)->default(0);
            $table->timestamps();
        });

        // Table des transferts
        Schema::create('transfer_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('reference')->unique();
            $table->timestamps();
        });

        // Table des transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('type')->default('purchase');
            $table->string('status')->default('pending');
            $table->string('reference')->unique();
            $table->foreignId('transfer_id')->nullable()->constrained('transfer_history')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id'); 
            $table->string('message', 255); 
            $table->string('status', 50)->default('info'); 
            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade'); // ON DELETE CASCADE 
        });

         // table des agents dgi
         Schema::create('dgis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('adress');
            $table->string('contact');
            $table->timestamps();
        });
        
          // table des transmissions
          Schema::create('transmissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('operators')->onDelete('cascade');
            $table->foreignId('dgi_id')->constrained('dgis')->onDelete('cascade');
            $table->string('nif','10');
            $table->decimal('chiffre_daffaire', 10, 2);
            $table->decimal('taux', 5, 2);
            $table->decimal('droit_daccise', 10, 2);
            $table->boolean('status')->default(false); // false = non imprimé, true = imprimé
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transfer_history');
        Schema::dropIfExists('transaction_logs');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('operators');
        Schema::dropIfExists('dgis');
        Schema::dropIfExists('transmissions');
    }
};
