# Budget_Calculator
Class project for CS 312 - Server Side Scripting 

Project Proposal: Budget Calculator
By Junpei Osterloth, Cam Mitchell, Tanner Brown

This project will help calculate future budgets by evaluating potential purchases with user decided criteria. It is the goal of this project to create an effective tool to help users avoid overspending while shopping.   

User Stories:
As someone trying to budget for a car, I need to set up a spending limit on the amount of money that should be spent each month to save up.

As someone worried about overspending, I need a way to generate a budget report.

As someone ready to make a potential purpose, I need to check how much of my total budget would remain afterwards.

Minimum Viable Product:
The bare minimum functionality required for this project to be considered a success. 
User Interface that allows for ease of use while creating budgets. 
Users can see the total amount of money remaining, which gets updated each time a purchase is finalized. The funds can also be added to the budget. 
Users can select budget guidelines that determine if a purchase is considered overspending.
Users should be able to input information about potential purchases. (Ex: Name, Cost, Short Description) and see how it would affect the budget.

After all purchases are finalized, the Budget Calculator will calculate all purchases and generate a report to determine if the budget's guidelines were followed. 

Text Files can be read by the Budget Calculator to do bulk orders of products.

Extra Features:
These are features that should be added only if there is enough time before the project is due.

Chronological record of all purchases, showing net decrease/increase overtime. Change in total amount of funds will be visualized on a graph.

Add option for multiple accounts to be created. Each account will be password protected to prevent unauthorized access.

Add a “wishlist” that allows users to save potential purchases for later. These saved products can be added to confirmed purchases at any time.  


This project will involve heavy use of user inputs and php functions to generate budget reports. Input validation, arrays, and loops will all be used to facilitate the functionality of the Budget Calculator. A database will be used to store information regarding purchases, total funds, and other related information for later access. Additionally, file i/o operations will be utilized in both reading bulk orders, and creating the budget reports.   
