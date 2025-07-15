# Batch Management System Guide

## Overview

The Sales Tracker now includes a comprehensive batch management system that allows you to:

- Create product batches with multiple products
- Track batch completion status automatically
- Manage multiple investors with different share percentages
- Calculate profit shares automatically
- Monitor batch progress in real-time

## Key Features

### 1. Batch Creation and Management

**Creating a New Batch:**
- Navigate to a business → Product Batches → Create Batch
- Enter batch name, purchase date, total cost, and quantity
- Batch starts with "active" status

**Batch Status Tracking:**
- **Active**: Batch is in progress, products available for sale
- **Completed**: All products sold, batch marked as complete
- **Cancelled**: Batch cancelled (manual action)

### 2. Automatic Completion Detection

The system automatically detects when a batch should be marked as completed:

- When the last product in a batch is sold
- When `remaining_quantity` reaches 0
- Automatically sets `completion_date` to current date
- Triggers profit calculations

### 3. Investor Management

**Adding Investors:**
- Navigate to a business → Investments → Add Investment
- Select investor, product batch, amount, and investment date
- Share percentages are calculated automatically based on investment amounts

**Share Calculation:**
- Share percentage = (Individual Investment / Total Investment) × 100
- Automatically updated when investments are added/modified/removed
- Ensures total shares always equal 100%

### 4. Profit Sharing

**Automatic Profit Calculation:**
- Total Profit = Total Revenue - Total Cost
- Individual Profit Share = Total Profit × (Share Percentage / 100)
- Calculated in real-time as sales are recorded

**Profit Distribution:**
- Each investor receives their calculated profit share
- Displayed in batch details and investment summaries
- Supports both positive and negative profits

## Database Schema

### Product Batches Table
```sql
- id (Primary Key)
- business_id (Foreign Key)
- name (String)
- purchase_date (Date)
- total_cost (Decimal)
- total_quantity (Integer)
- status (Enum: active, completed, cancelled)
- completion_date (Date, nullable)
- created_at, updated_at
```

### Investments Table
```sql
- id (Primary Key)
- business_id (Foreign Key)
- user_id (Foreign Key)
- product_batch_id (Foreign Key)
- amount (Decimal)
- share_percentage (Decimal)
- invested_at (Date)
- created_at, updated_at
```

### Sales Table
```sql
- id (Primary Key)
- business_id (Foreign Key)
- product_batch_id (Foreign Key)
- sold_by (Foreign Key to users)
- quantity (Integer)
- sale_price (Decimal)
- sold_at (DateTime)
- created_at, updated_at
```

## API Endpoints

### Product Batches
- `GET /businesses/{business}/product-batches` - List all batches
- `GET /businesses/{business}/product-batches/create` - Create form
- `POST /businesses/{business}/product-batches` - Store new batch
- `GET /businesses/{business}/product-batches/{batch}` - Show batch details
- `GET /businesses/{business}/product-batches/{batch}/edit` - Edit form
- `PUT /businesses/{business}/product-batches/{batch}` - Update batch
- `DELETE /businesses/{business}/product-batches/{batch}` - Delete batch
- `POST /businesses/{business}/product-batches/{batch}/complete` - Mark as completed
- `POST /businesses/{business}/product-batches/{batch}/calculate-shares` - Calculate shares

### Investments
- `GET /businesses/{business}/investments` - List all investments
- `GET /businesses/{business}/investments/create` - Create form
- `POST /businesses/{business}/investments` - Store new investment
- `GET /businesses/{business}/investments/{investment}` - Show investment details
- `GET /businesses/{business}/investments/{investment}/edit` - Edit form
- `PUT /businesses/{business}/investments/{investment}` - Update investment
- `DELETE /businesses/{business}/investments/{investment}` - Delete investment

### Sales
- `GET /businesses/{business}/sales` - List all sales
- `GET /businesses/{business}/sales/create` - Create form
- `POST /businesses/{business}/sales` - Store new sale
- `GET /businesses/{business}/sales/{sale}` - Show sale details
- `GET /businesses/{business}/sales/{sale}/edit` - Edit form
- `PUT /businesses/{business}/sales/{sale}` - Update sale
- `DELETE /businesses/{business}/sales/{sale}` - Delete sale

## Business Logic

### Batch Completion Service

The `BatchCompletionService` handles all batch completion logic:

```php
// Check if batch should be completed
$batchCompletionService->checkAndCompleteBatch($batch);

// Calculate and store profit
$batchCompletionService->calculateAndStoreProfit($batch);

// Calculate investor shares
$shares = $batchCompletionService->calculateInvestorShares($batch);

// Get complete batch summary
$summary = $batchCompletionService->getBatchSummary($batch);
```

### Automatic Triggers

1. **Sale Creation/Update/Deletion**: Automatically checks if batch should be completed
2. **Investment Creation/Update/Deletion**: Automatically recalculates share percentages
3. **Batch Completion**: Automatically calculates and stores profit data

### Validation Rules

**Product Batch:**
- Name: required, max 255 characters
- Purchase date: required, valid date
- Total cost: required, numeric, min 0
- Total quantity: required, integer, min 1

**Investment:**
- User: required, must exist
- Product batch: required, must exist
- Amount: required, numeric, min 0
- Investment date: required, valid date

**Sale:**
- Product batch: required, must exist
- Quantity: required, integer, min 1, must not exceed remaining quantity
- Sale price: required, numeric, min 0
- Sale date: required, valid datetime

## Frontend Components

### Product Batches
- **Index**: Grid view of all batches with progress bars and status
- **Create**: Form to create new batches
- **Show**: Detailed view with investor shares and completion actions
- **Edit**: Form to edit existing batches

### Investments
- **Index**: Table view of all investments with calculated shares
- **Create**: Form to add new investors to batches
- **Show**: Detailed investment information
- **Edit**: Form to edit investment details

### Sales
- **Index**: Table view of all sales
- **Create**: Form to record new sales
- **Show**: Detailed sale information
- **Edit**: Form to edit sale details

## Usage Examples

### Creating a Complete Batch Workflow

1. **Create Business**: Set up a new business
2. **Create Batch**: Add a product batch with initial details
3. **Add Investors**: Invite investors and record their investments
4. **Record Sales**: Sell products from the batch
5. **Monitor Progress**: Track completion percentage and profit
6. **Automatic Completion**: System marks batch as complete when all products sold
7. **View Results**: See final profit distribution among investors

### Example Scenario

**Batch: "iPhone 15 Pro Batch #1"**
- Total Cost: £50,000
- Total Quantity: 100 units
- Investors: John (£30,000), Sarah (£20,000)
- Share Distribution: John 60%, Sarah 40%

**Sales Progress:**
- Day 1: Sold 25 units for £15,000
- Day 2: Sold 30 units for £18,000
- Day 3: Sold 45 units for £27,000
- **Total Revenue: £60,000**
- **Total Profit: £10,000**

**Final Distribution:**
- John's Profit Share: £6,000 (60% of £10,000)
- Sarah's Profit Share: £4,000 (40% of £10,000)

## Best Practices

1. **Regular Monitoring**: Check batch progress regularly
2. **Accurate Data**: Ensure all costs and quantities are accurate
3. **Timely Sales Recording**: Record sales promptly for accurate tracking
4. **Investor Communication**: Keep investors informed of batch progress
5. **Profit Verification**: Review calculated profits before distribution

## Troubleshooting

### Common Issues

1. **Batch Not Completing**: Check if all products are actually sold
2. **Incorrect Share Percentages**: Verify investment amounts are correct
3. **Negative Profits**: Review cost and revenue data for accuracy
4. **Missing Investors**: Ensure all investors are properly recorded

### Data Integrity

- The system prevents deletion of batches with existing sales or investments
- Share percentages are automatically recalculated when investments change
- Batch completion is automatic and cannot be manually overridden
- All financial calculations use decimal precision for accuracy

## Future Enhancements

Potential improvements for the batch management system:

1. **Batch Templates**: Predefined batch configurations
2. **Advanced Analytics**: Profit forecasting and trend analysis
3. **Investor Portal**: Direct access for investors to view their shares
4. **Automated Notifications**: Email/SMS alerts for batch completion
5. **Multi-Currency Support**: Support for different currencies
6. **Batch Splitting**: Ability to split batches into sub-batches
7. **Advanced Reporting**: Detailed financial reports and exports 