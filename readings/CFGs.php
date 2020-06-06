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
<a class=subhead name=contents href=#contents>Contents</a>

<UL class=style1 style="list-style-type:disc">
  <LI><A HREF="#overview">Overview</A></li>
  <LI><A HREF="#ex1">Example: Simple Arithmetic Expressions</A></li>
  <LI><A HREF="#def">Formal Definition</A></li>
  <LI><A HREF="#ex2">Example: Boolean Expressions, Assignment Statements, and If Statements</A></li>
  <LI><A HREF="#youtry1">Test Yourself #1</A></li>
  <LI><A HREF="#lang">The Language Defined by a CFG</A></li>
	<UL class=style1 style="list-style-type:circle">
    <LI><A HREF="#der">Leftmost and Rightmost Derivations</A></li>
    <LI><A HREF="#tree">Parse Trees</A></li>
    <LI><A HREF="#youtry2">Test Yourself #2</A></li>
  </UL>
  <LI><A HREF="#ambig">Ambiguous Grammars</A></li>
  <LI><A HREF="#exp">Expression Grammars</A></li>
	<UL class=style1 style="list-style-type:circle">
    <LI><A HREF="#prec">Precedence</A></li>
    <LI><A HREF="#assoc">Associativity</A></li>
		<UL class=style1 style="list-style-type:square">
      <LI><A HREF="#youtry3">Test Yourself #3</A></li>
    </UL>
    <li><A HREF="#youtry4">Test Yourself #4</A></li>
  </UL>
  <LI><A HREF="#list">List Grammars</A></li>
  <LI><A HREF="#pl">A Grammar for a Programming Language</A></li>
  <LI><A HREF="#summ">Summary</A></li>
</UL>

<span class=subhead name=overview>Overview</span>
<a href=#overview></a>
<P>
Recall that
the input to the parser is a sequence of tokens (received interactively,
via calls to the scanner).
The parser:
<UL class=style1 style="list-style-type:disc">
  <LI> Groups the tokens into &quotgrammatical phrases&quot.
  <LI> Discovers the underlying structure of the program.
  <LI> Finds syntax errors.
  <LI> Perhaps also performs some actions to find other kinds of errors.
</UL>
The output depends on whether the input is a syntactically legal program;
if so, then the output is some representation of the program:
<!--<UL class=style1 style="list-style-type:disc">-->
<UL class=style1 style="list-style-type:disc">
  <LI> an abstract-syntax tree (maybe + a symbol table),
  <LI> or intermediate code,
  <LI> or object code.
</UL>
We know that we can use regular expressions to define languages
(for example, the languages of the tokens to be recognized by the scanner).
Can we use them to define the language to be recognized by the parser?
Unfortunately, the answer is no.
Regular expressions are not powerful enough to define many aspects
of a programming language's syntax.
For example, a regular expression cannot be used to specify that the
parentheses in an expression must be balanced, or that every
``else'' statement has a corresponding ``if''.
Furthermore, a regular expression doesn't say anything about
underlying <em>structure</em>.
For example, the following regular expression defines integer arithmetic
involving addition, subtraction, multiplication, and division:
<UL>
<pre>
digit+ (("+" | "-" | "*" | "/") digit+)*
</pre>
</UL>
but provides no information about the precedence and associativity of
the operators.
<P>
So to specify the syntax of a programming language, we use a different
formalism, called <b>context-free grammars</b>.
<P>
<a name=ex1></a>
<a name=ex1 href=#ex1 class=example>Simple Arithmetic Expressions</a>
<P>
We can write a context-free grammar (CFG) for the language of (very simple)
arithmetic expressions involving only subtraction and division.
In English:
<UL class=style1 style="list-style-type:disc">
  <LI> An integer is an arithmetic expression.
  <LI> If exp<sub>1</sub> and exp<sub>2</sub> are arithmetic expressions,
       then so are the following:
<UL class=style1 style="list-style-type:disc">
      <LI> exp<sub>1</sub> - exp<sub>2</sub>
      <LI> exp<sub>1</sub> / exp<sub>2</sub>
      <LI> ( exp<sub>1</sub> )
    </UL>
</UL>
Here is the corresponding CFG:
<a name=exampleExpGrammar></a>
<UL><tt>
exp </tt>&rarr;<tt> INTLITERAL        <BR>
exp </tt>&rarr;<tt> exp MINUS exp        <BR>
exp </tt>&rarr;<tt> exp DIVIDE exp        <BR>
exp </tt>&rarr;<tt> LPAREN exp RPAREN
</tt></UL>
And here is how to understand the grammar:
<UL>
  <LI> The grammar has five <b>terminal</b> symbols:
       <tt>INTLITERAL MINUS DIVIDE LPAREN RPAREN</tt>.
       The terminals of a grammar used to define a programming language
       are the tokens returned by the scanner.
  <P>
  <LI> The grammar has one <b>nonterminal</b>: <tt>exp</tt>
       (note that a single name, <tt>exp</tt>, is used instead of
       <tt>exp<sub>1</sub></tt> and <tt>exp<sub>2</sub></tt> as in the
       English definition above).
  <P>
  <LI> The grammar has four <b>productions</b> or <b>rules</b>,
       each of the form: <tt>exp </tt>&rarr;<tt> ...</tt>
       A production left-hand side is a single nonterminal.
       A production right-hand side is either the special symbol $\varepsilon$
       (the same $\varepsilon$ that can be used in a regular expression) or a
       sequence of one
       or more terminals and/or nonterminals (there is no rule with
       $\varepsilon$ on the right-hand side in the example given above).
</UL>
<P>
A more compact way to write this grammar is:
<UL>
<tt>
exp </tt>&rarr;<tt> INTLITERAL | exp MINUS exp | exp DIVIDE exp | LPAREN exp RPAREN
</tt>
</UL>
<P>
Intuitively, the vertical bar means ``or'', but do <b>not</b> be fooled
into thinking that the right-hand sides of grammar rules can contain
regular expression operators!
This use of the vertical bar is just shorthand for writing multiple
rules with the same left-hand-side nonterminal.
</p>
<a name=def href=#def class=subhead>Formal Definition</a>
<P>
A CFG is a 4-tuple $\left( N, \Sigma, P, S \right)$ where:
<UL class=style1 style="list-style-type:disc">
  <LI> $N$ is a set of nonterminals.
  <LI> $\Sigma$ is a set of terminals.
  <LI> $P$ is a set of productions (or rules).
  <LI> $S$ is the start nonterminal (sometimes called the goal nonterminal) in
       $N$.  If not specified, then it is the nonterminal that 
       appears on the left-hand side of the first production.
</UL>

<P>
<a name=ex2></a>
<H3>Example: Boolean Expressions, Assignment Statements, and If Statements</H3>
<P>
The language of boolean expressions can be defined in English as follows:
<UL>
  <LI> "true" is a boolean expression, recognized by the token <tt>TRUE</tt>.
  <LI> "false" is a boolean expression, recognized by the token <tt>FALSE</tt>.
  <LI> If exp<sub>1</sub> and exp<sub>2</sub> are boolean expressions, then so are the following:
    <UL>
      <LI> exp<sub>1</sub> || exp<sub>2</sub>
      <LI> exp<sub>1</sub> && exp<sub>2</sub>
      <LI> ! exp<sub>1</sub>
      <LI> ( exp<sub>1</sub> )
    </UL>
</UL>
Here is the corresponding CFG:
<UL>
<tt>
bexp </tt>&rarr;<tt> TRUE                 <BR>
bexp </tt>&rarr;<tt> FALSE                <BR>
bexp </tt>&rarr;<tt> bexp OR bexp         <BR>
bexp </tt>&rarr;<tt> bexp AND bexp        <BR>
bexp </tt>&rarr;<tt> NOT bexp             <BR>
bexp </tt>&rarr;<tt> LPAREN bexp RPAREN
</tt>
</UL>
Here is a CFG for a language of very simple assignment statements
(only statements that assign a boolean value to an identifier):
<UL>
<tt>
stmt </tt>&rarr;<tt> ID ASSIGN bexp SEMICOLON
</tt>
</UL>
We can ``combine'' the two grammars given above, and add two more rules
to get a grammar that defines the language of (very simple) if statements.
In words, an if statement is:
<OL>
  <LI> The word "if", followed by a boolean expression in parentheses,
       followed by a statement, or
  <LI> The word "if", followed by a boolean expression in parentheses,
       followed by a statement, followed by the word "else", followed
       by a statement.
</OL>
And here's the grammar:
<UL>
<tt>
stmt </tt>&rarr;<tt> IF LPAREN bexp RPAREN stmt                 <BR>
stmt </tt>&rarr;<tt> IF LPAREN bexp RPAREN stmt ELSE stmt       <BR>
stmt </tt>&rarr;<tt> ID ASSIGN bexp SEMICOLON                   <BR>
bexp </tt>&rarr;<tt> TRUE                                       <BR>
bexp </tt>&rarr;<tt> FALSE                                      <BR>
bexp </tt>&rarr;<tt> bexp OR bexp                               <BR>
bexp </tt>&rarr;<tt> bexp AND bexp                              <BR>
bexp </tt>&rarr;<tt> NOT bexp                                   <BR>
bexp </tt>&rarr;<tt> LPAREN bexp RPAREN                         <BR>
</tt>
</UL>
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
Write a context-free grammar for the language of very simple while loops
(in which the loop body only contains one statement) by adding a new production
with nonterminal <tt>stmt</tt> on the left-hand side.
<p><a href="SOLUTIONS/CFG-ANSWERS.html#ans1">solution</a></p>
<hr>
<P>
<a name=lang href=#lang class=subhead>The Language Defined by a CFG</a>
<P>
The language defined by a context-free grammar is the set of strings
(sequences of terminals) that can be <b>derived</b> from the start
nonterminal.
What does it mean to derive something?

<UL>
  <LI> Start by setting the &quotcurrent sequence&quot to be the start nonterminal.
  <LI> Repeat:
    <UL>
    <LI> find a nonterminal <font color=red>$X$</font> in the current sequence;
    <LI> find a production in the grammar with <font color=red>$X$</font> on the
         left (i.e., of the form <tt><font color=red>$X$ </tt>&rarr;<tt> $\alpha$</font></tt>,
	 where $\alpha$ is either $\varepsilon$ (the empty string) or a 
	 sequence of terminals and/or nonterminals);
    <LI> Create a new &quotcurrent sequence&quot in which
         <font color=red>$\alpha$</font>
         replaces the <font color=red>$X$</font> found above;
    </UL>
     until the current sequence contains no nonterminals.
</UL>
Thus we arrive either at epsilon or at a string of terminals.
That is how we derive a string in the language defined by a CFG.
<P>
Below is an example derivation, using the 4 productions for the grammar
of arithmetic expressions given <a href="#ex1">above</a>.
In this derivation, we use the actual lexemes instead of the token names
(e.g., we use the symbol "-" instead of MINUS).
<UL>
</pre>
exp $\longrightarrow$ exp - exp $\longrightarrow$ 1 - exp $\longrightarrow$ 1 - exp / exp $\longrightarrow$
1 - exp / 2 $\longrightarrow$ 1 - 4 / 2
</pre>
</UL>
And here is some useful notation:
<UL style="margin-top:10px; list-style:none">
  <LI style="padding-bottom:10px"> $\Longrightarrow$ means <b>derives in one step</b>
  <LI style="padding-bottom:10px"> $\stackrel{+}\Longrightarrow$ means <b>derives in one or more steps</b>
  <LI style="padding-bottom:10px"> $\stackrel{*}\Longrightarrow$ means <b>derives in zero or more steps</b>
</UL>
So, given the above example, we could write:
<tt>exp $\stackrel{+}\Longrightarrow$ 1 - exp / exp</tt>.
<P>
A more formal definition of what it means for
a CFG $G$ to define a language may be stated as follows:
<div align=center style="text-align:left">
          $$\mathcal{L}(G) = \left\{ w \middle| S \stackrel{+}\longrightarrow w\right\}	$$
					where
<ul>
<li>$S$ is the start nonterminal of $G$</li>
<li>$w$ is a sequence of terminals or $\varepsilon$</li>
</ul>
</div>
<a name=der href=#der class=subhead>Leftmost and Rightmost Derivations</a>
<P>
There are several kinds of derivations that are important.
A derivation is a <b>leftmost</b> derivation if it is
always the leftmost nonterminal that is chosen to
be replaced.
It is a <b>rightmost</b> derivation if it is always the rightmost one.
</p>
<a name=tree href=#tree class=subhead>Parse Trees</a>
<P>
Another way to derive things using a context-free grammar is to
construct a <b>parse tree</b> (also called a derivation tree) as follows:
<UL>
  <LI> Start with the start nonterminal.
  <LI> Repeat:
    <UL>
    <LI> choose a leaf nonterminal $X$
    <LI> choose a production $X \longrightarrow \alpha$
    <LI> the symbols in $\alpha$ become the children of $X$ in the tree
    </UL>
  until there are no more leaf nonterminals left.
</UL>
The derived string is formed by reading the leaf nodes from left to right.
<P>
Here is the example expression grammar given above:
<UL>
<tt>
exp </tt>&rarr;<tt> INTLITERAL
<BR>exp </tt>&rarr;<tt> exp MINUS exp
<BR>exp </tt>&rarr;<tt> exp DIVIDE exp
<BR>exp </tt>&rarr;<tt> LPAREN exp RPAREN
</tt>
</UL>
and, using that grammar, here's a parse tree for the
string <tt>1 - 4 / 2</tt>:
<div style="margin-top:20px" align=center><img src=diagrams/parse_left.png></div>
<P>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Below is the CFG for very simple if statements used earlier.
<UL>
<tt>
stmt </tt>&rarr;<tt> IF LPAREN bexp RPAREN stmt
<BR>stmt </tt>&rarr;<tt> IF LPAREN bexp RPAREN stmt ELSE stmt
<BR>stmt </tt>&rarr;<tt> ID ASSIGN bexp SEMICOLON
<BR>bexp </tt>&rarr;<tt> TRUE
<BR>bexp </tt>&rarr;<tt> FALSE
<BR>bexp </tt>&rarr;<tt> bexp OR bexp
<BR>bexp </tt>&rarr;<tt> bexp AND bexp
<BR>bexp </tt>&rarr;<tt> NOT bexp
<BR>bexp </tt>&rarr;<tt> LPAREN bexp RPAREN
</tt>
</UL>
<b>Question 1:</b>
Give a derivation for the string:
<tt>if (! true ) x = false;</tt>
Is your derivation leftmost, rightmost, or neither?
<P>
<b>Question 2:</b>
Give a parse tree for the same string.
<p><a href="SOLUTIONS/CFG-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
<a name=ambig class=subhead>Ambiguous Grammars</a>
<P>
The string <tt>1 - 4 / 2</tt> has <em>two</em> parse trees using the
example expression grammar.
One was given above; here's the other one:
<div style="margin-top:20px; margin-bottom:20px" align=center>
	<img src=diagrams/parse_right.png>
</div>
<P>
If for grammar $G$ and string $w$ there is:
<UL>
  <LI> more than one leftmost derivation of $w$ or,
  <LI> more than one rightmost derivation of $w$, or
  <LI> more than one parse tree for $w$
</UL>
then G is called an <b>ambiguous</b> grammar.
(Note: the three conditions given above are equivalent;
if one is true then all three are true.)
<P>
In general, ambiguous grammars cause problems:
<UL>
  <LI> Ambiguity can make parsing difficult.
  <LI> The underlying structure of the language defined by an
       ambiguous grammar is ill-defined (in the above example, the relative
       precedences of subtraction and division are not uniquely defined;
       the first parse tree groups 4/2 while the second groups 1-4, and
       those two groupings correspond to expressions with <em>different</em>
       values).
</UL>
<P>
<a name=exp href=#exp class=subhead>Expression Grammars</a>
<P>
Since every programming language includes expressions, it is useful to
know how to write a grammar for an expression language so that the grammar
correctly reflects the precedences and associativities of the operators.
<P>
<a name=prec href=prec class=subsubhead>Precedence</a>
<P>
To write a grammar whose parse trees express precedence correctly,
use a different nonterminal for each precedence level.
Start by writing a rule for the operator(s) with the lowest precedence
("-" in our case), then write a rule for the operator(s) with the next
lowest precedence, etc:
<UL>
<tt>
exp    </tt>&rarr;<tt> exp MINUS exp | term
<BR>term   </tt>&rarr;<tt> term DIVIDE term | factor
<BR>factor </tt>&rarr;<tt> INTLITERAL | LPAREN exp RPAREN
</tt>
</UL>
Now let's try using these new rules to build parse trees for
<tt>1 - 4 / 2</tt>.
First, a parse tree that correctly reflects that fact that division
has higher precedence than subtraction:
<div style="margin-top:20px; margin-bottom:20px" align=center>
	<img src=diagrams/parse_precedence.png>
</div>

Now we'll try to construct a parse tree that shows the <em>wrong</em>
precedence:
<div style="margin-top:20px; margin-bottom:20px" align=center>
	<img src=diagrams/parse_bad.png>
</div>

<a name=assoc href=#assoc class=subsubhead>Associativity</a>
<P>
This grammar captures operator precedence, but it is still ambiguous!
Parse trees using this grammar may not correctly express the fact that
both subtraction and division are <em>left</em> associative; e.g., the
expression:
<tt>5-3-2</tt> is equivalent to: <tt>((5-3)-2)</tt> and <em>not</em> to:
<tt>(5-(3-2))</tt>.
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Draw two parse trees for the expression <tt>5-3-2</tt> using the
current expression grammar:
<UL>
<tt>
exp    </tt>&rarr;<tt> exp MINUS exp | term
<BR>term   </tt>&rarr;<tt> term DIVIDE term | factor
<BR>factor </tt>&rarr;<tt> INTLITERAL | LPAREN exp RPAREN
</tt>
</UL>
One of your parse trees should correctly group <tt>5-3</tt>, and the
other should incorrectly group <tt>3-2</tt>.
<p><a href="SOLUTIONS/CFG-ANSWERS.html#ans3">solution</a></p>
<hr>
To understand how to write expression grammars that correctly reflect
the associativity of the operators, you need to understand about
<b>recursion</b> in grammars.
<UL>
  <LI> A grammar is <i>recursive in nonterminal $X$</i> if: $$X \stackrel{+}\longrightarrow {\dots}X{\dots}$$
       (in one or more steps, $X$ derives a sequence of symbols that includes
       an $X$).
	</li>
	<LI> A grammar is <i><b>left</b> recursive in $X$</i> if: 
	$$X \stackrel{+}\longrightarrow X...$$
       (in one or more steps, $X$ derives a sequence of symbols that
       <em>starts</em> with an $X$).
	</li>
	<li> A grammar is <i><b>right</b> recursive in $X$</i> if: 
	$$X \stackrel{+}\longrightarrow {\dots}X$$
       (in one or more steps, $X$ derives a sequence of symbols that
       <em>ends</em> with an $X$).
	</li>
</UL>
<p>
The grammar given above for arithmetic expressions is both left and
right recursive in nonterminals <tt>exp</tt> and <tt>term</tt> (can
you write the derivation steps that show this?).
</p>
<P>
To write a grammar that correctly expresses operator associativity:
<UL>
  <LI> For left associativity, use left recursion.
  <LI> For right associativity, use right recursion.
</UL>
Here's the correct grammar:
<UL>
<tt>
exp    </tt>&rarr;<tt> exp MINUS term     | term
<BR>term   </tt>&rarr;<tt> term DIVIDE factor | factor
<BR>factor </tt>&rarr;<tt> INTLITERAL         | LPAREN exp RPAREN
</tt>
</UL>
And here's the (one and only) parse tree that can be built
for <tt>5 - 3 - 2</tt> using this grammar:
<div style="margin-top:20px" align=center><img src=diagrams/parse_unambig.png></div>
<P>
Now let's consider a more complete expression grammar, for arithmetic
expressions with addition, multiplication, and exponentiation, as well
as subtraction and division.
We'll use the token POW for the exponentiation operator, and we'll use
"**" as the corresponding lexeme; e.g., "two to the third power" would
be written: <tt>2 ** 3</tt>, and the corresponding sequence of tokens
would be: <tt>INTLITERAL POW INTLITERAL</tt>.
Here's an ambiguous context-free grammar for this language:
<table>
<TR>
<TD><tt>exp</tt><td>&rarr;</td>
<TD><tt>exp PLUS exp  |  exp MINUS exp      |  exp TIMES exp  |  exp DIVIDE exp</tt></td>
</tr>
<tr>
<td></td><td><tt>|</tt></td><td><tt>exp POW exp   |  LPAREN exp RPAREN  | INTLITERAL</tt></td>
</tr>
</table>
<p>
First, we'll modify the grammar so that parse trees correctly reflect
the fact that addition and subtraction have the same, lowest
precedence; multiplication and division have the same, middle
precedence; and exponentiation has the highest precedence:
</p>
<UL>
<table style="width:auto">
<TR><TD><tt>exp</tt></td>
		<TD style="padding-right:20px ; padding-left:10px">&rarr;</td>
		<td><tt> exp PLUS exp</tt></td>
    <td><tt>|  exp MINUS exp</tt></td>
    <TD><tt>|  term</tt>
</tr>
<TR><TD><tt>term</tt></td>
		<TD style="padding-right:20px ; padding-left:10px">&rarr;</td>
    <TD><tt> term TIMES term</tt>
    <TD><tt>|  term DIVIDE term  </tt>
    <TD><tt>|  factor</tt>
</tr>
<TR><TD><tt>factor</tt>
		<TD style="padding-right:20px ; padding-left:10px">&rarr;</td>
    <TD><tt> factor POW factor  </tt>
    <TD><tt>|  exponent </tt>
</tr>
<TR><td><tt>exponent</tt>
		<TD style="padding-right:20px ; padding-left:10px">&rarr;</td>
		<td><tt>INTLITERAL</td>
		<td><tt> |  LPAREN exp RPAREN</tt></td>
</tr>
</table>
</UL>
This grammar is still ambiguous; it
does not yet reflect the associativities of the operators.
So next we'll modify the grammar so that parse trees correctly reflect the
fact that all of the operators except exponentiation are left associative (and
exponentiation is right associative; e.g., <tt>2**3**4</tt> is equivalent
to: <tt>2**(3**4)</tt>):
<UL>
<table>
<TR><TD><tt>exp</tt>
    <TD>&rarr;<tt> exp PLUS term</tt>
   <TD><tt>|  exp MINUS term</tt>
   <TD><tt>|  term</tt></td>
<TR><TD><tt>term</tt></td>
    <TD>&rarr;<tt> term TIMES factor</tt>
    <TD><tt>|  term DIVIDE factor</tt>
    <TD><tt>|  factor</tt>
<TR><TD><tt>factor
    <TD>&rarr;<tt> exponent POW factor</tt>
    <TD><tt>|  exponent</tt>
<TR><TD><tt>exponent</tt>
    <TD>&rarr;<tt> INTLITERAL</tt>
    <TD><tt>|  LPAREN exp RPAREN</tt>
</table>
</UL>
Finally, we'll modify the grammar by adding a <b>unary</b> operator,
unary minus,
which has the highest precedence of all (e.g., <tt>-3**4</tt> is
equivalent to: <tt>(-3)**4</tt>, not to <tt>-(3**4)</tt>.
Note that the notion of associativity does not apply to unary operators,
since associativity only comes into play in an expression of the form:
<tt>x op y op z</tt>.
<UL>
<table>
<TR><TD><tt>exp</tt>	
    <TD>&rarr;<tt> exp PLUS term</tt>
    <TD><tt>|  exp MINUS term</tt>
    <TD><tt>|  term</tt>
<TR><TD><tt>term</tt>
    <TD>&rarr;<tt> term TIMES factor</tt>
    <TD><tt> |  term DIVIDE factor</tt>
    <TD><tt>  |  factor</tt>
<TR><TD><tt>factor</tt> 
    <TD>&rarr;<tt> exponent POW factor</tt>
    <TD><tt> |  exponent</tt>
<TR><TD><tt>exponent
    <TD>&rarr;<tt> MINUS exponent</tt>
    <TD><tt> |  final</tt>
<TR><TD><tt>final</tt>
    <TD>&rarr;<tt> INTLITERAL</tt>
    <TD><tt>|  LPAREN exp RPAREN</tt>
</table>
</UL>
<P>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Below is the grammar we used earlier for the language of boolean
expressions, with two possible operands: <tt>true false</tt>, and
three possible operators: <tt>and or not</tt>:
<UL>
<tt>bexp </tt>&rarr;<tt> TRUE</tt><BR>
<tt>bexp </tt>&rarr;<tt> FALSE</tt><BR>
<tt>bexp </tt>&rarr;<tt> bexp OR bexp</tt><BR>
<tt>bexp </tt>&rarr;<tt> bexp AND bexp</tt><BR>
<tt>bexp </tt>&rarr;<tt> NOT bexp</tt><BR>
<tt>bexp </tt>&rarr;<tt> LPAREN bexp RPAREN</tt><BR>
</tt>
</UL>
<b>Question 1:</b>
Add nonterminals so that <tt>or</tt> has lowest precedence, then
<tt>and</tt>, then <tt>not</tt>.
Then change the grammar to reflect the fact that both
<tt>and</tt> and <tt>or</tt> are left associative.
<P>
<b>Question 2:</b>
Draw a parse tree (using your final grammar for Question 1)
for the expression: <tt>true and not true</tt>.
<p><a href="SOLUTIONS/CFG-ANSWERS.html#ans3">solution</a></p>
<hr>
<P>
<a name=list href=list class=subhead>List Grammars</a>
<P>
Another kind of grammar that you will often need to write is a grammar
that defines a <b>list</b> of something.
There are several common forms.
For each form given below, we provide three
different grammars that define the specified list language.

<p>
<UL>
  <LI> One or more <tt>PLUS</tt>es (without any separator or terminator).
       (Remember, <em>any</em> of the following three grammars defines
       this language; you don't need all three lines).
			 <ol>
       <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <i>xList</i> <i>xList</i></li>
       <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <i>xList</i> <tt>PLUS</tt></i></li>
       <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <tt>PLUS</tt>  <i>xList</i> </li>
			 </ol>
	</li>

  <P>
<!------- -->
  <LI> One or more <tt>PLUS</tt>es, separated by commas:
			 <ol>
			 <li style="list-style:decimal">
					<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <i>xList</i> <tt>COMMA</tt> <i>xList</i>
			</li>
			 <li style="list-style:decimal">
					<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <i>xList</i> <tt>COMMA</tt> <tt>PLUS</tt> 
			</li>
			<li style="list-style:decimal">
					<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> | <tt>PLUS</tt> <tt>COMMA</tt> <i>xList</i>
			</li>
			 </ol>
	</li>

<!------- -->
  <LI> One or more <tt>PLUS</tt>es, each <tt>PLUS</tt> <em>terminated</em> by a semi-colon:
	<ol>
		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> <tt>SEMICOLON</tt> | <i>xList</i> <i>xList</i>
		 </li>
		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> <tt>SEMICOLON</tt> | <i>xList</i> <tt>PLUS</tt> <tt>SEMICOLON</tt>
		 </li>

		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ <tt>PLUS</tt> <tt>SEMICOLON</tt> | <tt>PLUS</tt> <tt>SEMICOLON</tt> <i>xList</i> 
		 </li>
	</ol>
	</LI>

<!------- -->
  <LI> Zero or more <tt>PLUS</tt>es (without any separator or terminator):
			 <ol>
			 <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ 
			 $\varepsilon$ | <tt>PLUS</tt> | <i>xList</i> <i>xList</i>
			 </li>
			 <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ 
			 $\varepsilon$ | <tt>PLUS</tt> | <i>xList</i> <tt>PLUS</tt></i></li>
			 <li style="list-style:decimal"><i>xList</i> $\longrightarrow$ 
			 $\varepsilon$ | <tt>PLUS</tt> | <tt>PLUS</tt>  <i>xList</i> </li>
			 </ol>
  </li>

<!------- -->
  <LI> Zero or more <tt>PLUS</tt>es, each <tt>PLUS</tt> <em>terminated</em> by a semi-colon:
	<ol>
		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ 
			$\varepsilon$ | <tt>PLUS</tt> <tt>SEMICOLON</tt> | <i>xList</i> <i>xList</i>
		 </li>
		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ 
			$\varepsilon$ | <tt>PLUS</tt> <tt>SEMICOLON</tt> | <i>xList</i> <tt>PLUS</tt> <tt>SEMICOLON</tt>
		 </li>

		 <li style="list-style:decimal">
			<i>xList</i> $\longrightarrow$ 
			$\varepsilon$ | <tt>PLUS</tt> <tt>SEMICOLON</tt> | <tt>PLUS</tt> <tt>SEMICOLON</tt> <i>xList</i> 
		 </li>
	</ol>
<li>
The trickiest kind of list is a list of
zero or more x's, separated by commas.
To get it right, think of the definition as follows:
       <UL>
       <em>Either an empty list, or a non-empty list of x's separated
       by commas.</em>
       </UL>
       We already know how to write a grammar for a non-empty list of
       x's separated by commas, so now it's easy to write the grammar:
			<br>
				<table style="width:auto">
				<tr>
				<td align="right"><i>xList</i></td>
				<td style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
				<td align="left">$\varepsilon$ | <i>nonemptyList</i>
				</tr>
				<tr>
				<td align="right" style="padding-right:10px"><i>nonemptyList</i></td>
				<td style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
				<td align="left"><tt>PLUS</tt> | <tt>PLUS</tt> <tt>COMMA</tt> <i>nonemptyList</i></td>
				</tr>
				</table>
</li>
</UL>
<P>
<a name=pl href=#pl class=subhead>A Grammar for a Programming Language</a>
<P>
To write a grammar for a whole programming language,
break down the problem into pieces.
For example, think about a simple Java program, which consists of
one or more classes:
</p>
<table style="width:auto">
<tr>
<td align="right"><i>program</i></td>
<td style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>classList</i>
</tr>
<tr>
<td align="right"><i>classlist</i></td>
<td style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>class</i>&nbsp|&nbsp<i>class &nbsp classList</i>
</tr>
</table>
<p>
A class is the word "class", optionally preceded by the word "public",
followed by an identifier, followed by an open curly brace, followed by
the class body, followed by a closing curly brace:
</p>
<table style="width:auto">
<tr>
<td align="right"><i>class</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><tt>PUBLIC</tt> <tt>CLASS</tt> <tt>ID</tt> <tt>LCURLY</tt> <i>classbody</i> <tt>RCURLY</tt></td>
</tr>
<tr>
<td align="right"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">|</td>
<td align="left"><tt>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</tt> <tt>CLASS</tt> <tt>ID</tt> <tt>LCURLY</tt> <i>classbody</i> <tt>RCURLY</tt></td>
</tr>
</table>
<p>
A class body is a list of zero or more field and/or method definitions:
</p>
<table style="width:auto">
<tr>
<td align="right"><i>classbody</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left">$\varepsilon$
</tr>
<tr>
<td align="right"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">|</td>
<td align="left"><i>deflist</i>
</tr>
<tr>
<td align="right"><i>deflist</i></td>
<td align="center" style="padding-left:10px ; padding-right:10px">$\longrightarrow$</td>
<td align="left"><i>def</i>
</tr>
<tr>
<td align="right"></td>
<td align="center" style="padding-left:10px ; padding-right:10px">|</td>
<td align="left"><i>def</i>&nbsp&nbsp<i>deflist</i>
</tr>
</table>
<p>
and so on.
</p>
<a name=summ href=#summ class=subhead>Summary</a>
<P>
To understand how a parser works, we start by understanding <b>context-free
grammars</b>, which are used to define the language recognized by the parser.
Important terminology includes:
<UL>
  <LI> terminal symbol
  <LI> nonterminal symbol
  <LI> grammar rule (or production)
  <LI> derivation (leftmost derivation, rightmost derivation)
  <LI> parse (or derivation) tree
  <LI> the language defined by a grammar
  <LI> ambiguous grammar
</UL>
<P>
Two common kinds of grammars are grammars for <b>expression</b>
languages, and grammar for <b>lists</b>.
It is important to know how to write a grammar for an expression
language that expresses operator precedence and associativity.
It is also important to know how to write grammars for both non-empty
and possibly empty lists, and for lists both with and without separators
and terminators.

</body></html>
