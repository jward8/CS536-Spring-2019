<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<HR>
<a name=contents href=#contents class=subhead>Contents</a>

<UL>
  <LI><A HREF="#overview">Motivation and Definition</A>
  <LI><A HREF="#ex1">Example 1: Value of an Arithmetic Expression</A>
  <LI><A HREF="#ex2">Example 2: Type of an Expression</A>
  <LI><A HREF="#youtry1">Test Yourself #1</A>
  <LI><A HREF="#ast">Building an Abstract-Syntax Tree</A>
    <UL>
    <LI><A HREF="#astVsParse">The AST vs the Parse Tree</A>
    <LI><A HREF="#trans">Translation Rules That Build an AST</A>
    <LI><A HREF="#youtry2">Test Yourself #2</A>
    </UL>
</UL>

<P>
<a name=overview href=#overview class=subhead>Motivation and Definition</a>
<P>
Recall that the parser must produce output (e.g., an abstract-syntax
tree) for the next phase of the compiler.
This involves doing a <b>syntax-directed translation</b> -- translating
from a sequence of tokens to some other form, based on the underlying
syntax.
<P>
A syntax-directed translation is defined by augmenting the CFG:
a <b>translation rule</b> is defined for each production.
A translation rule defines the translation of the left-hand side nonterminal
as a function of:
<UL>
  <LI> constants
  <LI> the right-hand-side nonterminals' translations
  <LI> the right-hand-side tokens' values (e.g., the integer value
       associated with an <tt>INTLITERAL</tt> token, or the String value associated
       with an ID token)
</UL>
To translate an input string:
<OL>
  <LI> Build the parse tree.
  <LI> Use the translation rules to compute the translation of each nonterminal
       in the tree, working bottom up (since a nonterminal's value may depend
       on the value of the symbols on the right-hand side, you need to work
       bottom-up so that those values are available).
</OL>
The translation of the string is the translation of the parse tree's root
nonterminal.
</p>
<a name=ex1 href=#ex1 class=subhead>Example 1</a>
<P>
Below is the definition of a syntax-directed translation that
translates an arithmetic expression to its integer value.
When a nonterminal occurs more than once in a grammar rule, the
corresponding translation rule uses subscripts to identify a
particular instance of that nonterminal.
For example, the rule <i>exp</i> $\rightarrow$ <i>exp</i> <tt>PLUS</tt> <i>term</i> has two <i>exp</i> nonterminals;
<i>exp<sub>1</sub></i> means the left-hand-side <i>exp</i>, and
<i>exp<sub>2</sub></i> means the right-hand-side <i>exp</i>.
Also, the notation <tt>xxx.value</tt> is used to mean the value associated
with token <tt>xxx</tt>.

<table style="width:auto">
<tr>
<td colspan=3 style="align:center; border-bottom:1px solid"><b>CFG Production</b></td>
<td style="padding-left:60px"></td>
<td style="align:center; border-bottom:1px solid"><b>Translation rules</b></td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>exp</i> <tt>PLUS</tt> <i>term</i>
<td></td>
<td>exp<sub>1</sub>.trans = exp<sub>2</sub>.trans + <i>term</i>.trans</td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>term</i></td>
<td></td>
<td><i>exp</i>.trans = <i>term</i>.trans</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>term</i> <tt>TIMES</tt> <i>factor</i></td>
<td></td>
<td><i>term</i><sub>1</sub>.trans = <i>term</i><sub>2</sub>.trans * <i>factor</i>.trans</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>factor</i></td>
<td></td>
<td><i>term</i>.trans = <i>factor</i>.trans</td>
</tr>

<tr>
<td align="right"><i>factor</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>INTLITERAL</tt></td>
<td></td>
<td><i>factor</i>.trans = <tt>INTLITERAL</tt>.value</td>
</tr>

<tr>
<td align="right"><i>factor</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>LPARENS</tt> <i>exp</i> <tt>RPARENS</tt></td>
<td></td>
<td><i>factor</i>.trans = <i>exp</i>.trans</td>
</tr>
</table>
<p>
consider evaluating these rules on the input <tt>2 * (4 + 5)</tt>. The result is the following annotated parse tree:
<div style="margin-top:20px" align=center><img src=diagrams/sdt_annotated.png></div>
<P>
<a name=ex2 href=#ex2 class=subhead>Example 2</a>
<P>
Consider a language of expressions that use
the three operators: +, &&, == using the terminal symbols <tt>PLUS</tt>, <tt>AND</tt>
, <tt>EQUALS</tt>, respectively. Integer literals are represented by
the same <tt>INTLITERAL</tt> token we've used before, and <tt>TRUE</tt> and <tt>FALSE</tt>
represent the literals true and false (note that we could have just as well defined a
single <tt>BOOLLITERAL</tt> token that the scanner would populate with either true or false).</p> 
<p>
Let's define a syntax-directed translation that type checks these
expressions; i.e., for type-correct expressions, the translation
will be the type of the expression (either <b>int</b> or <b>bool</b>), and
for expressions that involve type errors, the translation will be
the special value <b>error</b>.
We'll use the following type rules:
<OL class=style1>
  <LI> Both operands of the + operator must be of type <b>int</b>.
  <LI> Both operands of the && operator must be of type <b>bool</b>.
  <LI> Both operands of the == operator must have the same (non-<b>error</b>) type.
</OL>

Here is the CFG and the translation rules:
<table style="width:auto">
<tr>
<td colspan=3 style="align:center; border-bottom:1px solid"><b>CFG Production</b></td>
<td style="padding-left:60px"></td>
<td style="align:center; border-bottom:1px solid"><b>Translation rules</b></td>
</tr>

<tr style="padding-bottom:70px">
<td align="right"><i>exp</i></td>
<td align="center" style="padding-bottom:8em; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>exp</i> &nbsp<tt>PLUS</tt>&nbsp <i>term</i>
<td></td>
<td>if (<i>exp</i><sub>2</sub>.trans == <b>int</b> and (<i>term</i>.trans == <b>int</b>) then
<br />
&nbsp&nbsp&nbsp&nbsp&nbsp <i>exp</i><sub>1</sub>.trans = <b>int</b>
<br />
else
<br /> 
&nbsp&nbsp&nbsp&nbsp&nbsp<i>exp</i><sub>1</sub>.trans = <b>error</b>
</td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-bottom:8em; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>exp</i> &nbsp<tt>AND</tt>&nbsp <i>term</i>
<td></td>
<td>if (<i>exp</i><sub>2</sub>.trans == <b>bool</b> and (<i>term</i>.trans == <b>bool</b>) then
<br />
&nbsp&nbsp&nbsp&nbsp&nbsp <i>exp</i><sub>1</sub>.trans = <b>bool</b>
<br />
else
<br /> 
&nbsp&nbsp&nbsp&nbsp&nbsp<i>exp</i><sub>1</sub>.trans = <b>error</b>
</td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-bottom:120px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>exp</i> &nbsp<tt>EQUALS</tt>&nbsp <i>term</i>
<td></td>
<td>if (<i>exp</i><sub>2</sub>.trans == <i>term</i>.trans) and (<i>term</i>.trans $\neq$ <b>error</b>) then
<br />
&nbsp&nbsp&nbsp&nbsp&nbsp <i>exp</i><sub>1</sub>.trans = <b>bool</b>
<br />
else
<br /> 
&nbsp&nbsp&nbsp&nbsp&nbsp<i>exp</i><sub>1</sub>.trans = <b>error</b>
</td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-bottom:40px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>term</i>
<td></td>
<td><i>exp</i>.trans = <i>term</i>.trans
</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-bottom:40px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>TRUE</tt>
<td></td>
<td><i>term</i>.trans = <b>bool</b>
</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-bottom:40px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>FALSE</tt>
<td></td>
<td><i>term</i>.trans = <b>bool</b>
</td>
</tr>


<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-bottom:40px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>INTLITERAL</tt>
<td></td>
<td><i>term</i>.trans = <b>int</b>
</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-bottom:40px; padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>LPARENS<tt>&nbsp<i>exp</i>&nbsp<tt>RPARENS</tt>
<td></td>
<td><i>term</i>.trans = <i>exp</i>.trans
</td>
</tr>


</table>

<p>
Here's an annotated parse tree for the input <tt>(2 + 2) == 4</tt>
</p>


<div style="margin-top:20px" align=center><img src=diagrams/sdt_annotated_2.png></div>
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
The following grammar defines the language of base-2 numbers:
<UL>
<pre>
B -> 0
  -> 1
  -> B 0
  -> B 1
</pre>
</UL>
Define a syntax-directed translation so that the translation of a binary
number is its base 10 value.
Illustrate your translation scheme by drawing the parse tree for
<tt>1001</tt> and annotating each nonterminal in the tree with
its translation.       

<p><a href="sdt_answers.php#ans1">solution</a></p>
<hr>
<P>
<a name=ast href=#ast class="subhead">Building an Abstract-Syntax Tree</a>
<P>
So far, our example syntax-directed translations have produced simple
values (an int or a type) as the translation of an input.
In practice however, we want the parser to build an abstract-syntax tree
as the translation of an input program.
But that is not really so different from what we've seen so far;
we just need to use tree-building operations in the translation
rules instead of, e.g., arithmetic operations.
<P>
<a name=astVsParse href=#astVsParse class=subsubhead>The AST vs the Parse Tree</a>
<P>
First, let's consider how an abstract-syntax tree (AST) differs
from a parse tree.
An AST can be thought of as a <em>condensed</em> form of the parse tree:
<UL>
  <LI> Operators appear at <em>internal</em> nodes instead of at leaves.
  <LI> "Chains" of single productions are collapsed.
  <LI> Lists are "flattened".
  <LI> Syntactic details (e.g., parentheses, commas, semi-colons) are
       omitted.
</UL>
In general, the AST is a better structure for later stages of the compiler
because it omits details having to do with the source language, and just
contains information about the essential structure of the program.
<P>
Below is an example of the parse tree and the AST for the expression
<tt>3 * (4 + 2)</tt> (using the usual arithmetic-expression grammar that
reflects the precedences and associativities of the operators).
Note that the parentheses are not needed in the AST because the structure of
the AST defines how the subexpressions are grouped.
</p>
<div style="margin-top:20px" align=center><img src=diagrams/sdt_parse_and_ast.png></div>
<P>
For constructs other than expressions, the compiler writer has some choices
when defining the AST -- but remember that lists (e.g., lists of declarations
lists of statements, lists of parameters) should be flattened, that operators
(e.g., "assign", "while", "if") go at internal nodes, not at leaves, and
that syntactic details are omitted.
<P>
<UL>
For example:
<pre>
Input
=====

{               
   x = 0;        
   while (x<10) { 
      x = x+1;     
   }      
   y = x*2;
}
</pre>

Parse Tree:
<div width=100% style="margin-top:20px" align=center><img src=diagrams/sdt_parse_and_ast_method.png></div>
Abstract Syntax Tree:
<div width=100% style="margin-top:20px" align=center><img src=diagrams/sdt_parse_and_ast_method_ast.png></div>

</UL>
Note that in the AST there is just one <tt>stmtList</tt> node, with a list of three
children (the list of statements has been "flattened").
Also, the "operators" for the statements (<tt>assign</tt> and
<tt>while</tt>) have been "moved up" to internal nodes (instead of
appearing as tokens at the leaves).
And finally, syntactic details (curly braces and semi-colons)
have been omitted.
<P>
<a name=trans href=#trans class=subhead>Translation Rules That Build an AST</a>
<P>
To define a syntax-directed translation so that the translation of an input
is the corresponding AST, we first need operations that create AST nodes.
Let's use java code, and assume that we have the following class hierarchy:
<UL>
<pre>
class ExpNode { }

class IntLitNode extends ExpNode {
    public IntLitNode(int val) {...}
}

class PlusNode extends ExpNode {
    public PlusNode( ExpNode e1, ExpNode e2 ) { ... }
}

class TimesNode extends ExpNode {
    public TimesNode( ExpNode e1, ExpNode e2 ) { ... }
}
</pre>
</UL>
Now we can define a syntax-directed translation for simple arithmetic expressions,
so that the translation of an expression is its AST:
<UL>

<table style="width:auto">
<tr>
<td colspan=3 style="align:center; border-bottom:1px solid"><b>CFG Production</b></td>
<td style="padding-left:60px"></td>
<td style="align:center; border-bottom:1px solid"><b>Translation rules</b></td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>exp</i> <tt>PLUS</tt> <i>term</i>
<td></td>
<td>exp<sub>1</sub>.trans = new PlusNode(<i>exp</i><sub>2</sub>, <i>term</i>.trans)</td>
</tr>

<tr>
<td align="right"><i>exp</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>term</i></td>
<td></td>
<td><i>exp</i>.trans = <i>term</i>.trans</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>term</i> <tt>TIMES</tt> <i>factor</i></td>
<td></td>
<td><i>term</i><sub>1</sub>.trans = new TimesNode(<i>term</i><sub>2</sub>.trans, <i>factor</i>.trans)</td>
</tr>

<tr>
<td align="right"><i>term</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>factor</i></td>
<td></td>
<td><i>term</i>.trans = <i>factor</i>.trans</td>
</tr>

<tr>
<td align="right"><i>factor</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>INTLITERAL</tt></td>
<td></td>
<td><i>factor</i>.trans = new IntLitNode(<tt>INTLITERAL</tt>.value)</td>
</tr>

<tr>
<td align="right"><i>factor</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>LPARENS</tt> <i>exp</i> <tt>RPARENS</tt></td>
<td></td>
<td><i>factor</i>.trans = <i>exp</i>.trans</td>
</tr>
</table>
<hr>
<a name=youtry2></a>
<P>
<center>
<u><a name=youtry><b>TEST YOURSELF #2</b></a></u>
</center></a>
<P>
Illustrate the syntax-directed translation defined above by drawing the
parse tree for the expression <tt>2 + 3 * 4</tt>, and annotating the
parse tree with its translation (i.e., each nonterminal in the tree will have
a pointer to the AST node that is the root of the subtree of the AST that
is the nonterminal's translation).
</pre>
</UL>
<p><a href="sdt_answers.php#ans2">solution</a></p>
<hr>
</body>
</html>
