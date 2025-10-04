<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            
            // Participants
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('LGA Admin or State Admin currently handling this chat');
            
            // Geographic Segregation (Critical for data security)
            $table->foreignId('lga_id')->constrained('lgas')->onDelete('restrict')
                  ->comment('Links chat to the farmers LGA for role-based filtering');
            
            // Chat Metadata
            $table->string('subject')->nullable()->comment('Optional subject line');
            $table->enum('status', [
                'open',           // Newly created, waiting for admin
                'in_progress',    // Admin is actively responding
                'pending_farmer', // Waiting for farmer response
                'resolved',       // Issue resolved
                'closed'          // Permanently closed
            ])->default('open');
            
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Timestamps
            $table->timestamp('last_message_at')->nullable()->comment('For sorting active chats');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['lga_id', 'status']);
            $table->index('farmer_id');
            $table->index('assigned_admin_id');
            $table->index('last_message_at');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade')
                  ->comment('Can be farmer user or admin user');
            
            // Message Content
            $table->text('body');
            $table->json('attachments')->nullable()->comment('Array of file paths');
            
            // Message Type
            $table->enum('sender_type', ['farmer', 'admin'])->comment('Quick identification of sender role');
            
            // Status Tracking
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['chat_id', 'created_at']);
            $table->index('sender_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chats');
    }
};