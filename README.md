# Benue State Smart Agricultural System: Feature Overview

This document provides a concise summary of the major implemented features, their purpose, and the established interaction flows within the system.

---

## 1. Core Modules & User Roles

| **Module**              | **Purpose**                                                                 | **Key User Roles Involved**                                          |
|-------------------------|-----------------------------------------------------------------------------|----------------------------------------------------------------------|
| **User & Access Management** | Defines permissions and access based on user hierarchy.                      | Super Admin, Governor, State Admin, LGA Admin, Agent, Farmer         |
| **Marketplace (Lead Gen)**   | Enables verified farmers to list their produce for sale to the public.        | Farmer (Lister), Public/Guest (Buyer), State Admin (Moderator)       |
| **Community Support**        | Provides real-time chat support and feedback loop for farmers and admins.    | Farmer, Enrollment Agent, LGA Admin, State Admin (Oversight)         |
| **Executive Oversight**      | Provides high-level data and analytics for strategic decision-making.        | Governor, State Admin                                                |

---

## 2. Feature Flow Details

### A. Role-Based Access Control (RBAC) Flow

**Mechanism:** Utilizes Laravel's built-in authentication and [Spatie Permissions](https://spatie.be/docs/laravel-permission) package.

| **Role**          | **Access Scope**         | **Key Permissions**                                                                      |
|-------------------|--------------------------|------------------------------------------------------------------------------------------|
| **Super Admin**   | Full System               | All management permissions (`manage_users`, `manage_roles`, etc.)                        |
| **Governor**      | Executive Oversight       | Read-only dashboards, state-wide reports, view all support chats                         |
| **State Admin**   | State-Wide Operations     | Manages LGAs/Agents, support chats (view/respond/manage), marketplace moderation         |
| **LGA Admin / Agent** | Local (Scoped by LGA)     | Manages local farmers and data, handles support chats for their LGA                      |
| **Farmer (User)** | Personal                  | Manages own profile, access to Marketplace, initiates support chats                      |

---

### B. Community Support (Real-Time Chat) Flow

**Purpose:** Instant, verified communication between Farmers and local admin support.

| **Step**         | **User Action**                                              | **System Action**                                                                                   | **Real-Time Component**                                          |
|------------------|--------------------------------------------------------------|------------------------------------------------------------------------------------------------------|------------------------------------------------------------------|
| **1. Initiation** | Farmer creates a new support chat                            | System links chat to Farmer's `lga_id`, broadcasts `ChatCreated` event                              | Broadcast to `lga-support.{lgaId}` via Laravel Echo/Reverb       |
| **2. Notification** | —                                                          | LGA Admin/Agent sees chat instantly, gets desktop/UI notification                                   | Uses Laravel Reverb/Echo for real-time push                      |
| **3. Response**  | LGA Admin/Agent responds to chat                             | System checks LGA scope via `routes/channels.php`                                                   | Messages broadcast on `chat.{chatId}`                            |
| **4. Oversight** | State Admin/Governor views support queue                     | State Admin can join/respond to any chat; Governor has read-only access                            | Oversight channels available to higher-level roles               |

---

### C. Marketplace (Lead Generation) Flow

**Purpose:** Connect verified farmers with buyers for off-platform negotiations.

| **Step**         | **User Action**                                              | **System Action**                                                                                   | **Revenue Model Gate**                                          |
|------------------|--------------------------------------------------------------|------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------|
| **1. Authorization** | Farmer tries to create/activate listing                   | System checks if annual listing fee is paid                                                         | Blocks listing if unpaid                                        |
| **2. Listing**   | Farmer submits product details (commodity, price, etc.)      | Product created with `status: pending_review`                                                       | —                                                               |
| **3. Moderation**| State Admin reviews and activates listing                    | Admin sets `status: active`                                                                         | —                                                               |
| **4. Public View** | Buyer browses public `/marketplace` page                   | System displays only `active` products, filters by LGA/Commodity                                    | —                                                               |
| **5. Lead Generation** | Buyer clicks "Contact Farmer" on listing                | Buyer’s contact is sent to Farmer via dashboard/email                                               | —                                                               |
| **6. Transaction** | Farmer contacts Buyer                                       | Negotiation, payment, and delivery happen off-platform                                              | —                                                               |

---

## 📝 Notes

- All real-time features use **Laravel Echo + Reverb** for push notifications and chat.
- **Spatie Permissions** ensures flexible and scalable role management.
- System is designed to **scale state-wide**, with role-specific scopes from LGA to State level.

---

## 📁 Tech Stack Highlights

- **Framework:** Laravel (PHP)
- **Real-time:** Laravel Echo + Reverb
- **RBAC:** Spatie Laravel-Permission
- **Frontend:** Blade / Vue.js (if applicable)
- **Database:** MySQL/PostgreSQL
- **Notifications:** Broadcasting via Channels & Events

---






## Issues to address
1 - support view accross roles, users should view charts, State Admin view chats as well
2 - Super Admin Analytics for different features
3 - Load LGAs on the governor dashboard policy analysis _ claude 7/10/2025
4 - Factor in the isolated Data Analytics Features - 5/10/2025
5 - Plan and execute market listing, farmers pay a token before their goods are listed.







