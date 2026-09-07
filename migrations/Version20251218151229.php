//
// Created by kylia on 04/12/2025.
//

#ifndef CLIONCODE_SDD_STACK_H
#define CLIONCODE_SDD_STACK_H
typedef struct StackNode {
    int value ;
    struct StackNode* next ;
}StackNode;

typedef struct Stack {
    StackNode* top ;
}Stack;

StackNode* create_StackNode(int value);
Stack* create_Stack();
int is_empty_Stack(Stack* stack);
void push(Stack* stack, int value);
int peek(Stack* stack);
int pop(Stack* stack);
#endif //CLIONCODE_SDD_STACK_H 